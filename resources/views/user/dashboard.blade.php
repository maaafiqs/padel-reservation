@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3 class="text-xl mb-6 text-primary">Menu Pengguna</h3>
        <nav class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="{{ route('user.reservations.create') }}" class="sidebar-link"><i class="fa-solid fa-calendar-plus"></i> Buat Reservasi</a>
            <a href="{{ route('user.reservations.index') }}" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking</a>
            <a href="{{ route('user.profile.edit') }}" class="sidebar-link"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                @csrf
                <button type="submit" class="sidebar-link text-danger w-full" style="text-align: left; background: none; border: none; cursor: pointer; display: flex; align-items: center; width: 100%; color: #ef4444; gap: 0.75rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-content">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl">Dashboard Saya</h1>
            <div class="flex items-center gap-4">
                <span class="text-muted">Halo, {{ auth()->user()->name }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="card mb-6 bg-primary-light" style="border-left: 4px solid var(--primary);">
                <p class="text-primary font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(isset($announcements) && $announcements->count() > 0)
            <div class="mb-6">
                @foreach($announcements as $announcement)
                    <div class="card mb-3" style="background-color: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 1rem 1.5rem;">
                        <h4 class="font-bold text-lg" style="color: #0369a1;"><i class="fa-solid fa-bullhorn mr-2"></i> {{ $announcement->title }}</h4>
                        <p class="mt-1" style="color: #0c4a6e;">{{ $announcement->content }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="card bg-primary-light">
                <h3 class="text-muted text-sm uppercase mb-2">Total Pesanan</h3>
                <p class="text-3xl font-bold text-primary">{{ $totalPesanan }}</p>
            </div>
            <div class="card">
                <h3 class="text-muted text-sm uppercase mb-2">Menunggu Pembayaran</h3>
                <p class="text-3xl font-bold text-warning">{{ $menungguPembayaran }}</p>
            </div>
            <div class="card">
                <h3 class="text-muted text-sm uppercase mb-2">Pesanan Selesai</h3>
                <p class="text-3xl font-bold text-success">{{ $pesananSelesai }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            @php
                $upcomingReservation = $reservations->where('reservation_date', '>=', date('Y-m-d'))->first();
            @endphp
            
            @if($upcomingReservation)
            <div class="card bg-primary-light">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl mb-1">Jadwal Main Mendatang</h3>
                        <p class="text-muted">{{ $upcomingReservation->court->name }}</p>
                    </div>
                    @if($upcomingReservation->status == 'pending')
                        @if($upcomingReservation->payment_proof)
                            <span class="badge badge-warning">Menunggu Verifikasi Admin</span>
                        @else
                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                        @endif
                    @elseif($upcomingReservation->status == 'confirmed')
                        <span class="badge badge-success">Dikonfirmasi</span>
                    @else
                        <span class="badge">{{ ucfirst($upcomingReservation->status) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-muted mb-4">
                    <span><i class="fa-regular fa-calendar mr-2"></i> {{ \Carbon\Carbon::parse($upcomingReservation->reservation_date)->translatedFormat('d F Y') }}</span>
                    <span><i class="fa-regular fa-clock mr-2"></i> {{ \Carbon\Carbon::parse($upcomingReservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($upcomingReservation->end_time)->format('H:i') }}</span>
                </div>
                
                @if($upcomingReservation->status == 'pending' && !$upcomingReservation->payment_proof)
                    <div class="border-t pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-semibold text-danger">Batas Waktu Pembayaran:</p>
                                <p class="text-lg font-bold" id="countdown-dashboard"></p>
                            </div>
                            <a href="{{ route('user.reservations.pay', $upcomingReservation->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                        </div>
                        <script>
                            // Countdown Timer Logic
                            (function() {
                                const expiryTime = new Date("{{ \Carbon\Carbon::parse($upcomingReservation->created_at)->addHours(24)->format('Y-m-d H:i:s') }}").getTime();
                                const countdownEl = document.getElementById('countdown-dashboard');
                                
                                const x = setInterval(function() {
                                    const now = new Date().getTime();
                                    const distance = expiryTime - now;
                                    
                                    if (distance < 0) {
                                        clearInterval(x);
                                        countdownEl.innerHTML = "Waktu Habis";
                                        setTimeout(() => window.location.reload(), 2000);
                                        return;
                                    }
                                    
                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    countdownEl.innerHTML = hours + "j " + minutes + "m " + seconds + "d ";
                                }, 1000);
                            })();
                        </script>
                    </div>
                @elseif($upcomingReservation->status == 'confirmed')
                    <div class="border-t pt-4 mt-2 text-right">
                        <a href="{{ route('user.reservations.download', $upcomingReservation->id) }}" target="_blank" class="btn btn-outline btn-sm" style="color: #166534; border-color: #166534;"><i class="fa-solid fa-download"></i> Unduh Tiket</a>
                    </div>
                @endif
            </div>
            @else
            <div class="card">
                <div class="flex flex-col justify-center h-full">
                    <h3 class="text-xl mb-1">Jadwal Main Mendatang</h3>
                    <p class="text-muted">Belum ada jadwal main mendatang.</p>
                </div>
            </div>
            @endif

            <div class="card flex flex-col justify-center items-center text-center">
                <i class="fa-solid fa-calendar-plus text-primary text-4xl mb-4"></i>
                <h3 class="text-xl mb-2">Ingin main lagi?</h3>
                <a href="{{ route('user.reservations.create') }}" class="btn btn-primary">Pesan Lapangan Baru</a>
            </div>
        </div>
        
        <h2 class="text-2xl mb-6">Reservasi Terbaru</h2>
        @if($reservations->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: var(--surface); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
                <thead style="background-color: var(--primary-light);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Tanggal</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Lapangan</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Waktu</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Harga Final</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Status</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--border);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations->take(5) as $reservation)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('d M Y') }}</td>
                        <td style="padding: 1rem;">{{ $reservation->court->name }}</td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
                        <td style="padding: 1rem;">Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</td>
                        <td style="padding: 1rem;">
                            @if($reservation->status == 'pending')
                                @if($reservation->payment_proof)
                                    <span class="badge badge-warning">Menunggu Verifikasi Admin</span>
                                @else
                                    <span class="badge badge-warning">Menunggu Pembayaran</span>
                                @endif
                            @elseif($reservation->status == 'confirmed')
                                <span class="badge badge-success">Dikonfirmasi</span>
                            @elseif($reservation->status == 'cancelled')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="badge">{{ ucfirst($reservation->status) }}</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($reservation->status == 'pending' && !$reservation->payment_proof)
                                <a href="{{ route('user.reservations.pay', $reservation->id) }}" class="text-primary font-semibold hover:underline">Bayar</a>
                            @elseif($reservation->status == 'confirmed' || $reservation->status == 'completed')
                                <a href="{{ route('user.reservations.download', $reservation->id) }}" target="_blank" class="text-success font-semibold hover:underline" style="color: #166534;"><i class="fa-solid fa-download"></i> Unduh Tiket</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="card text-center py-8">
                <p class="text-muted">Anda belum memiliki riwayat reservasi.</p>
            </div>
        @endif
    </main>
</div>
@endsection

