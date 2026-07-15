@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container flex justify-center items-center" style="min-height: calc(100vh - 80px); padding-top: 80px; position: relative;">
    <!-- Decorative Swinging Racket -->
    <div style="position: absolute; right: 5%; top: 50%; transform: translateY(-50%); z-index: -1; opacity: 0.3; pointer-events: none;" class="hidden md:block">
        <i class="fa-solid fa-table-tennis-paddle-ball" style="color: var(--primary); font-size: 25rem; animation: swing-paddle 4s ease-in-out infinite;"></i>
    </div>

    <div class="card" style="width: 100%; max-width: 450px; z-index: 10; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
        <div class="text-center mb-8">
            <h1 class="text-3xl mb-2">Selamat Datang Kembali</h1>
            <p class="text-muted">Masuk ke akun Anda untuk melanjutkan</p>
        </div>
        
        @if(session('error'))
            <div class="badge badge-danger mb-4 w-full justify-center" style="padding: 0.75rem; width: 100%; display: flex;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required autofocus>
                @error('email') <span class="text-danger text-sm" style="color: #991b1b;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="form-input" required style="padding-right: 2.5rem; width: 100%;">
                    <button type="button" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; outline: none;" onclick="togglePasswordVisibility('password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember">
                    <span class="text-sm">Ingat Saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary">Lupa password?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
        </form>

        <div class="text-center mt-6 text-sm text-muted">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-semibold">Daftar sekarang</a>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
