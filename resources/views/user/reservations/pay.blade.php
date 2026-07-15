@extends('layouts.app')

@section('title', 'Pembayaran Reservasi')

@section('content')
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3 class="text-xl mb-6 text-primary">Menu Pengguna</h3>
        <nav class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="{{ route('user.reservations.create') }}" class="sidebar-link"><i class="fa-solid fa-calendar-plus"></i> Buat Reservasi</a>
            <a href="{{ route('user.reservations.index') }}" class="sidebar-link active"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking</a>
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
            <h1 class="text-3xl">Pembayaran Reservasi</h1>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <!-- Rincian -->
            <div class="card h-fit">
                <h3 class="text-xl mb-4 text-primary font-semibold border-b pb-2">Rincian Tagihan</h3>
                
                @php
                    $start = \Carbon\Carbon::parse($reservation->start_time);
                    $end = \Carbon\Carbon::parse($reservation->end_time);
                    $durationInHours = $start->diffInMinutes($end) / 60;
                @endphp
                
                <div class="flex justify-between mb-3 border-b pb-2">
                    <span class="text-muted">Tanggal</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('d F Y') }}</span>
                </div>
                
                <div class="flex justify-between mb-3 border-b pb-2">
                    <span class="text-muted">Waktu</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} ({{ $durationInHours }} Jam)</span>
                </div>

                <div class="flex justify-between mb-3 border-b pb-2">
                    <span class="text-muted">Sewa Lapangan ({{ $reservation->court->name }})</span>
                    <span class="font-medium">
                        @php
                            $courtPrice = $reservation->court->price_per_hour * $durationInHours;
                        @endphp
                        Rp {{ number_format($courtPrice, 0, ',', '.') }}
                    </span>
                </div>

                @if($reservation->coach)
                <div class="flex justify-between mb-3 border-b pb-2">
                    <span class="text-muted">Sewa Pelatih ({{ $reservation->coach->name }})</span>
                    <span class="font-medium">
                        @php
                            $coachPrice = $reservation->coach->price_per_hour * $durationInHours;
                        @endphp
                        Rp {{ number_format($coachPrice, 0, ',', '.') }}
                    </span>
                </div>
                @endif

                @if($reservation->inventories->count() > 0)
                <div class="mt-4 mb-2">
                    <span class="text-muted font-semibold">Sewa Perlengkapan:</span>
                    @foreach($reservation->inventories as $inv)
                        <div class="flex justify-between mt-2 text-sm">
                            <span>{{ $inv->name }} (x{{ $inv->pivot->quantity }})</span>
                            <span class="font-medium">Rp {{ number_format($inv->pivot->price * $inv->pivot->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                @endif

                @if($reservation->discount_amount > 0)
                <div class="flex justify-between mb-2 mt-4 text-sm">
                    <span class="text-muted">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-3 border-b pb-2 text-sm text-success">
                    <span>Diskon ({{ $reservation->discount_code }})</span>
                    <span class="font-medium">- Rp {{ number_format($reservation->discount_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-4 mt-2">
                    <span class="text-lg font-semibold">Total Pembayaran</span>
                    <span class="text-xl font-bold text-primary">Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="flex justify-between mb-4 mt-6">
                    <span class="text-lg font-semibold">Total Pembayaran</span>
                    <span class="text-xl font-bold text-primary">Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="mt-6 p-4 rounded-md" style="background-color: var(--primary-light);">
                    <p class="font-semibold mb-2">Transfer ke salah satu rekening berikut:</p>
                    <ul class="list-none space-y-2">
                        <li class="flex items-center justify-between">
                            <span>Bank BCA</span>
                            <span class="font-bold">1234 567 890 (a.n. Maaafiqs Padel)</span>
                        </li>
                        <li class="flex items-center justify-between mt-2">
                            <span>Bank Mandiri</span>
                            <span class="font-bold">0987 654 321 (a.n. Maaafiqs Padel)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Form Upload -->
            <div class="card h-fit">
                <h3 class="text-xl mb-4 text-primary font-semibold border-b pb-2">Unggah Bukti Bayar</h3>
                
                @if($errors->any())
                    <div class="p-4 mb-4 text-sm" style="background-color: #fee2e2; color: #991b1b; border-radius: var(--radius-md);">
                        <ul class="list-disc pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.reservations.uploadPayment', $reservation->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    
                    <div class="form-group mb-6 mt-4">
                        <label class="form-label" for="payment_proof">File Bukti Transfer (JPG, PNG)</label>
                        <input type="file" name="payment_proof" id="payment_proof" class="form-input" required accept="image/jpeg,image/png,image/jpg" style="padding: 0.5rem; background: var(--background);">
                        <p class="text-sm text-muted mt-2">Ukuran file maksimal: 2MB.</p>
                    </div>

                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-upload mr-2"></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Custom Success Popup Modal -->
    <div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; justify-content: center; align-items: center;">
        <div class="card text-center animate-fade-in" style="max-width: 400px; width: 90%; background: var(--surface); padding: 2.5rem 2rem;">
            <div style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fa-solid fa-check text-4xl" style="color: #166534;"></i>
            </div>
            <h3 class="text-2xl font-bold mb-3">Unggah Berhasil!</h3>
            <p class="text-muted mb-6">Pembayaran berhasil dan akan diproses.</p>
            
            <div style="display: flex; justify-content: center; margin-top: 1rem;">
                <i class="fa-solid fa-circle-notch fa-spin text-primary text-xl"></i>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Tampilkan custom popup
            document.getElementById('successModal').style.display = 'flex';
            
            // Submit form secara otomatis setelah 2 detik
            setTimeout(() => {
                this.submit();
            }, 2000);
        });
    </script>
</div>
@endsection

