@extends('layouts.admin')

@section('title', 'Update Status Reservasi')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Update Status Reservasi</h1>
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="mb-6 grid grid-cols-2 gap-4">
        <div>
            <p class="text-muted mb-1">ID Booking:</p>
            <p class="font-medium text-lg text-primary">{{ $reservation->reservation_code ?? '#RES-' . str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div>
            <p class="text-muted mb-1">Pengguna:</p>
            <p class="font-medium text-lg">{{ $reservation->user ? $reservation->user->name : '-' }}</p>
        </div>
        <div>
            <p class="text-muted mb-1">Lapangan:</p>
            <p class="font-medium text-lg">{{ $reservation->court ? $reservation->court->name : '-' }}</p>
        </div>
        <div>
            <p class="text-muted mb-1">Jadwal:</p>
            <p class="font-medium text-lg">
                {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}<br>
                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
            </p>
        </div>
        <div class="col-span-2">
            <p class="text-muted mb-1">{{ __('Equipment Rental:') }}</p>
            @if($reservation->inventories->count() > 0)
                <ul class="list-disc pl-5 mt-1 text-sm font-medium">
                    @foreach($reservation->inventories as $inv)
                        <li>{{ $inv->name }} ({{ $inv->pivot->quantity }}x) - Rp {{ number_format($inv->pivot->price * $inv->pivot->quantity, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="font-medium text-sm text-gray-500 mt-1">- Tidak menyewa perlengkapan -</p>
            @endif
        </div>
        <div class="col-span-2 mt-4 p-4 rounded-md" style="background-color: var(--background); border: 1px solid var(--border);">
            <h3 class="font-semibold text-lg mb-3 border-b pb-2">Rincian Biaya</h3>
            
            @php
                $start = \Carbon\Carbon::parse($reservation->start_time);
                $end = \Carbon\Carbon::parse($reservation->end_time);
                $durationInHours = $start->diffInMinutes($end) / 60;
                $courtPrice = $reservation->court ? $reservation->court->price_per_hour * $durationInHours : 0;
            @endphp
            
            <div class="flex justify-between mb-2 text-sm">
                <span class="text-muted">Sewa Lapangan ({{ $durationInHours }} Jam)</span>
                <span class="font-medium">Rp {{ number_format($courtPrice, 0, ',', '.') }}</span>
            </div>

            @if($reservation->coach)
            @php
                $coachPrice = $reservation->coach->price_per_hour * $durationInHours;
            @endphp
            <div class="flex justify-between mb-2 text-sm">
                <span class="text-muted">Sewa Pelatih ({{ $reservation->coach->name }})</span>
                <span class="font-medium">Rp {{ number_format($coachPrice, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <hr style="border: 0; border-top: 1px dashed var(--border); margin: 0.5rem 0;">

            <div class="flex justify-between mb-2 text-sm mt-2">
                <span class="text-muted">Subtotal (Lapangan, Pelatih, Perlengkapan)</span>
                <span class="font-medium">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
            </div>
            
            @if($reservation->discount_amount > 0)
            <div class="flex justify-between mb-2 text-sm text-success">
                <span>Diskon ({{ $reservation->discount_code }})</span>
                <span class="font-medium">- Rp {{ number_format($reservation->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <hr style="border: 0; border-top: 1px dashed var(--border); margin: 0.5rem 0;">
            
            <div class="flex justify-between mt-2 items-center">
                <span class="font-semibold text-lg">Total Pembayaran</span>
                <span class="font-bold text-xl text-primary">Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0;">

    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Ubah Status</label>
            <select name="status" class="form-input" required>
                <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Sudah Dibayar)</option>
                <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed (Selesai Bermain)</option>
                <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Update Status') }}</button>
    </form>
</div>
@endsection
