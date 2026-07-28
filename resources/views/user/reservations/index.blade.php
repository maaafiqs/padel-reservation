@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="dashboard-layout">
    @include('user.partials.sidebar')

    <!-- Main Content -->
    <main class="dashboard-content">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl">Riwayat Booking</h1>
            <a href="{{ route('user.reservations.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> Reservasi Baru</a>
        </div>

        @if($reservations->count() > 0)
        <div class="card mb-6 p-0" style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; white-space: nowrap;">
                    <thead style="background-color: var(--primary-light);">
                        <tr>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Registration No.') }}</th>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Playing Schedule') }}</th>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Court Details') }}</th>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Total Payment') }}</th>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Status') }}</th>
                            <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text); text-align: center;">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 1rem 1.5rem;">
                                <span class="font-bold text-primary">{{ $reservation->reservation_code ?? '-' }}</span>
                            </td>
                            <td style="padding: 1rem 1.5rem;">
                                <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('d F Y') }}</div>
                                <div class="text-muted text-sm mt-1"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</div>
                            </td>
                            <td style="padding: 1rem 1.5rem;">
                                <div style="font-weight: 500;">{{ $reservation->court->name }}</div>
                                @if($reservation->coach)
                                <div class="text-muted text-sm mt-1"><i class="fa-solid fa-user-tie"></i> {{ $reservation->coach->name }}</div>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.5rem; font-weight: 500;">
                                Rp {{ number_format($reservation->final_price, 0, ',', '.') }}
                            </td>
                            <td style="padding: 1rem 1.5rem;">
                                @if($reservation->status == 'pending')
                                    @if($reservation->payment_proof)
                                        <span class="badge badge-warning" style="padding: 0.35rem 0.75rem;">{{ __('Awaiting Verification') }}</span>
                                    @else
                                        <span class="badge badge-warning" style="padding: 0.35rem 0.75rem;">{{ __('Awaiting Payment') }}</span>
                                    @endif
                                @elseif($reservation->status == 'confirmed')
                                    <span class="badge badge-success" style="padding: 0.35rem 0.75rem;">{{ __('Confirmed') }}</span>
                                @elseif($reservation->status == 'cancelled')
                                    <span class="badge badge-danger" style="padding: 0.35rem 0.75rem;">{{ __('Cancelled') }}</span>
                                @else
                                    <span class="badge" style="padding: 0.35rem 0.75rem;">{{ ucfirst($reservation->status) }}</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: center;">
                                @if($reservation->status == 'pending' && !$reservation->payment_proof)
                                    <a href="{{ route('user.reservations.pay', $reservation->id) }}" class="btn btn-primary btn-sm" style="padding: 0.4rem 1rem; border-radius: 9999px;">{{ __('Pay') }}</a>
                                @elseif($reservation->status == 'confirmed' || $reservation->status == 'completed')
                                    <a href="{{ route('user.reservations.download', $reservation->id) }}" target="_blank" class="btn btn-outline btn-sm" style="padding: 0.4rem 1rem; border-radius: 9999px; color: #166534; border-color: #166534; background-color: #f0fdf4;"><i class="fa-solid fa-download mr-1"></i> {{ __('Ticket') }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Component -->
        <div class="flex justify-center mt-6">
            {{ $reservations->links('pagination::simple-tailwind') }}
        </div>
        @else
            <div class="card text-center py-16">
                <i class="fa-solid fa-folder-open text-4xl text-muted mb-4"></i>
                <h3 class="text-xl mb-2">Belum ada reservasi</h3>
                <p class="text-muted mb-6">Anda belum pernah melakukan pemesanan lapangan.</p>
                <a href="{{ route('user.reservations.create') }}" class="btn btn-primary">Pesan Sekarang</a>
            </div>
        @endif
    </main>
</div>
@endsection

