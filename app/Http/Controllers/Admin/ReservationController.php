<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
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

        $query = Reservation::with(['user', 'court']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('reservation_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('court', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $oldStatus = $reservation->status;
        $newStatus = $validated['status'];

        // Status yang berarti barang sudah tidak dipakai (dikembalikan/batal)
        $inactiveStatuses = ['cancelled', 'completed'];
        
        // Status yang berarti barang sedang dipakai (ter-booking)
        $activeStatuses = ['pending', 'confirmed'];

        // Jika berubah dari active ke inactive -> kembalikan stok
        if (in_array($newStatus, $inactiveStatuses) && in_array($oldStatus, $activeStatuses)) {
            foreach ($reservation->inventories as $inv) {
                \App\Models\Inventory::where('id', $inv->id)->increment('stock', $inv->pivot->quantity);
            }
        } 
        // Jika berubah dari inactive ke active -> kurangi stok lagi
        elseif (in_array($newStatus, $activeStatuses) && in_array($oldStatus, $inactiveStatuses)) {
            foreach ($reservation->inventories as $inv) {
                \App\Models\Inventory::where('id', $inv->id)->decrement('stock', $inv->pivot->quantity);
            }
        }

        $reservation->update(['status' => $newStatus]);
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        // Return stock before deleting if it wasn't cancelled yet
        if ($reservation->status !== 'cancelled') {
            foreach ($reservation->inventories as $inv) {
                \App\Models\Inventory::where('id', $inv->id)->increment('stock', $inv->pivot->quantity);
            }
        }
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Reservasi berhasil dihapus.');
    }
}
