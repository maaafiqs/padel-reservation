@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3 class="text-xl mb-6 text-primary">Menu Pengguna</h3>
        <nav class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="{{ route('user.reservations.create') }}" class="sidebar-link"><i class="fa-solid fa-calendar-plus"></i> Buat Reservasi</a>
            <a href="{{ route('user.reservations.index') }}" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking</a>
            <a href="{{ route('user.profile.edit') }}" class="sidebar-link active"><i class="fa-solid fa-user"></i> Profil Saya</a>
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
            <h1 class="text-3xl">Profil Saya</h1>
        </div>

        @if(session('success'))
            <div class="card mb-6 bg-primary-light" style="border-left: 4px solid var(--primary);">
                <p class="text-primary font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="card mb-6" style="background-color: #fee2e2; border-left: 4px solid #991b1b;">
                <ul style="color: #991b1b; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="max-width: 600px;">
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-6">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group mb-6">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-input" required value="{{ old('email', $user->email) }}">
                </div>

                <div class="form-group mb-6">
                    <label for="role" class="form-label">Peran</label>
                    <input type="text" id="role" class="form-input" disabled value="{{ ucfirst($user->role) }}" style="background-color: var(--background); cursor: not-allowed;">
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

