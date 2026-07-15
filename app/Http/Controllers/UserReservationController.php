<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserReservationController extends Controller
{
    public function dashboard()
    {
        // Cancel expired reservations
        $expired = \App\Models\Reservation::where('status', 'pending')
            ->whereNull('payment_proof')
            ->where('created_at', '<', \Carbon\Carbon::now()->subHours(24))
            ->get();
            
        foreach ($expired as $exp) {
            foreach ($exp->inventories as $inv) {
                \App\Models\Inventory::where('id', $inv->id)->increment('stock', $inv->pivot->quantity);
            }
            $exp->update(['status' => 'cancelled']);
        }

        $reservations = \App\Models\Reservation::where('user_id', auth()->id())
            ->with(['court', 'coach'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->take(5) // Just show latest 5
            ->get();
            
        // User Stats
        $totalPesanan = \App\Models\Reservation::where('user_id', auth()->id())->count();
        $menungguPembayaran = \App\Models\Reservation::where('user_id', auth()->id())->where('status', 'pending')->count();
        $pesananSelesai = \App\Models\Reservation::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count();

        // Fetch Announcements
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.dashboard', compact(
            'reservations',
            'totalPesanan',
            'menungguPembayaran',
            'pesananSelesai',
            'announcements'
        ));
    }

    public function index()
    {
        // Cancel expired reservations
        $expired = \App\Models\Reservation::where('status', 'pending')
            ->whereNull('payment_proof')
            ->where('created_at', '<', \Carbon\Carbon::now()->subHours(24))
            ->get();
            
        foreach ($expired as $exp) {
            foreach ($exp->inventories as $inv) {
                \App\Models\Inventory::where('id', $inv->id)->increment('stock', $inv->pivot->quantity);
            }
            $exp->update(['status' => 'cancelled']);
        }

        $reservations = \App\Models\Reservation::where('user_id', auth()->id())
            ->with(['court', 'coach'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        return view('user.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $courts = \App\Models\Court::where('status', 'available')->get();
        $coaches = \App\Models\Coach::all();
        $discounts = \App\Models\Discount::where('is_active', true)
            ->where(function ($query) {
                $query->whereDate('valid_until', '>=', today())
                      ->orWhereNull('valid_until');
            })
            ->get();
        $inventories = \App\Models\Inventory::where('stock', '>', 0)->get();
            
        return view('user.reservations.create', compact('courts', 'coaches', 'discounts', 'inventories'));
    }

    public function getBookedSlots(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date',
        ]);

        $slots = \App\Models\Reservation::where('court_id', $request->court_id)
            ->where('reservation_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->select('start_time', 'end_time')
            ->get()
            ->map(function ($res) {
                return [
                    'start' => \Carbon\Carbon::parse($res->start_time)->format('H:i'),
                    'end' => \Carbon\Carbon::parse($res->end_time)->format('H:i')
                ];
            });

        return response()->json($slots);
    }

    public function getAvailableCoaches(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $coaches = \App\Models\Coach::where('is_available', true)->get();
        
        $availableCoaches = [];
        foreach ($coaches as $coach) {
            // Count overlapping reservations for this coach on this date
            $overlappingCount = \App\Models\Reservation::where('coach_id', $coach->id)
                ->where('reservation_date', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })->count();

            $remainingSlots = $coach->capacity - $overlappingCount;
            if ($remainingSlots > 0) {
                $availableCoaches[] = [
                    'id' => $coach->id,
                    'name' => $coach->name,
                    'remaining_slots' => $remainingSlots,
                    'price_per_hour' => $coach->price_per_hour
                ];
            }
        }

        return response()->json($availableCoaches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'coach_id' => 'nullable|exists:coaches,id',
            'reservation_date' => 'required|date|after:today', // H-1 minimal (besok)
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Note: Frontend sends 23:59 for 00:00 end time.
        // Check for overlapping reservations
        $overlapping = \App\Models\Reservation::where('court_id', $request->court_id)
            ->where('reservation_date', $request->reservation_date)
            ->whereIn('status', ['pending', 'confirmed']) // Only check active reservations
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($overlapping) {
            return back()->withInput()->withErrors(['time_error' => 'Jadwal lapangan sudah terisi pada waktu tersebut.']);
        }

        $court = \App\Models\Court::findOrFail($request->court_id);
        
        // Coach capacity check
        if ($request->coach_id) {
            $coach = \App\Models\Coach::findOrFail($request->coach_id);
            $overlappingCount = \App\Models\Reservation::where('coach_id', $coach->id)
                ->where('reservation_date', $request->reservation_date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })->count();
                
            if ($overlappingCount >= $coach->capacity) {
                return back()->withInput()->withErrors(['coach_error' => 'Maaf, slot untuk pelatih ini sudah penuh pada waktu tersebut.']);
            }
        }
        
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $durationInHours = $start->diffInMinutes($end) / 60;
        
        $totalPrice = $court->price_per_hour * $durationInHours;

        // If coach selected, add coach price (assuming coach has price_per_hour)
        if ($request->coach_id) {
            $coach = \App\Models\Coach::findOrFail($request->coach_id);
            // Assuming the coach has a price_per_hour column in the database? Let's check or just ignore if they don't.
            // Let's assume they don't or it's handled differently if it doesn't exist, I will use try-catch or just check if property exists
            if (isset($coach->price_per_hour)) {
                 $totalPrice += ($coach->price_per_hour * $durationInHours);
            } elseif (isset($coach->specialty)) { // Coach model exists, just skip pricing if not defined
                // Do nothing
            }
        }

        // Calculate inventory cost and prepare sync data
        $inventoryCost = 0;
        $syncData = [];
        
        if ($request->has('inventories') && is_array($request->inventories)) {
            foreach ($request->inventories as $inventoryId => $data) {
                if (isset($data['quantity']) && $data['quantity'] > 0) {
                    $inventory = \App\Models\Inventory::find($inventoryId);
                    if ($inventory && $inventory->stock >= $data['quantity']) {
                        $inventoryCost += ($inventory->price * $data['quantity']);
                        $syncData[$inventoryId] = [
                            'quantity' => $data['quantity'],
                            'price' => $inventory->price
                        ];
                    }
                }
            }
        }

        $totalPrice += $inventoryCost;
        
        $finalPrice = $totalPrice;
        $discountAmount = 0;
        $discountCode = null;

        if ($request->filled('promo_code')) {
            $discount = \App\Models\Discount::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('valid_until')
                      ->orWhereDate('valid_until', '>=', today());
                })->first();

            if ($discount) {
                if ($discount->type === 'nominal') {
                    $discountAmount = $discount->nominal_amount;
                } else {
                    $discountAmount = ($totalPrice * $discount->percentage) / 100;
                }
                
                // Prevent final price from becoming negative
                if ($discountAmount > $totalPrice) {
                    $discountAmount = $totalPrice;
                }
                
                $finalPrice = $totalPrice - $discountAmount;
                $discountCode = $discount->code;
            } else {
                return back()->withInput()->withErrors(['promo_error' => 'Kode promo tidak valid atau sudah kedaluwarsa.']);
            }
        }

        $reservationCode = 'RES-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        $reservation = \App\Models\Reservation::create([
            'reservation_code' => $reservationCode,
            'user_id' => auth()->id(),
            'court_id' => $request->court_id,
            'coach_id' => $request->coach_id,
            'reservation_date' => $request->reservation_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $totalPrice,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'status' => 'pending',
        ]);

        if (!empty($syncData)) {
            $reservation->inventories()->attach($syncData);
            
            // Decrement stock
            foreach ($syncData as $inventoryId => $details) {
                \App\Models\Inventory::where('id', $inventoryId)->decrement('stock', $details['quantity']);
            }
        }

        return redirect()->route('user.dashboard')->with('success', 'Reservasi berhasil dibuat! Silakan unggah bukti pembayaran Anda.');
    }

    public function pay(\App\Models\Reservation $reservation)
    {
        // Ensure user owns this reservation and it's pending without proof
        if ($reservation->user_id !== auth()->id() || $reservation->status !== 'pending' || $reservation->payment_proof !== null) {
            abort(404);
        }

        // Check if expired
        if ($reservation->created_at < \Carbon\Carbon::now()->subHours(24)) {
            $reservation->update(['status' => 'cancelled']);
            return redirect()->route('user.dashboard')->withErrors(['error' => 'Waktu pembayaran telah habis.']);
        }

        return view('user.reservations.pay', compact('reservation'));
    }

    public function uploadPayment(Request $request, \App\Models\Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() || $reservation->status !== 'pending' || $reservation->payment_proof !== null) {
            abort(404);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $reservation->update(['payment_proof' => $path]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    public function downloadTicket(\App\Models\Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() || !in_array($reservation->status, ['confirmed', 'completed'])) {
            abort(404);
        }

        return view('user.reservations.ticket', compact('reservation'));
    }
}
