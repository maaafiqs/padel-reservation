@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="container flex justify-center items-center" style="min-height: calc(100vh - 80px); padding-top: 80px; padding-bottom: 40px; position: relative;">
    <!-- Decorative Swinging Racket -->
    <div style="position: absolute; left: 5%; top: 50%; transform: translateY(-50%) scaleX(-1); z-index: -1; opacity: 0.3; pointer-events: none;" class="hidden md:block">
        <i class="fa-solid fa-table-tennis-paddle-ball" style="color: var(--primary); font-size: 25rem; animation: swing-paddle 4s ease-in-out infinite;"></i>
    </div>

    <div class="card" style="width: 100%; max-width: 500px; z-index: 10; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
        <div class="text-center mb-8">
            <h1 class="text-3xl mb-2">Buat Akun Baru</h1>
            <p class="text-muted">Bergabunglah dan mulai reservasi lapangan padel.</p>
        </div>
        
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                @error('name') <span class="text-danger text-sm" style="color: #991b1b;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
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
                @error('password') <span class="text-danger text-sm" style="color: #991b1b;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required style="padding-right: 2.5rem; width: 100%;">
                    <button type="button" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; outline: none;" onclick="togglePasswordVisibility('password_confirmation', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar Akun</button>
        </form>

        <div class="text-center mt-6 text-sm text-muted">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-semibold">Masuk di sini</a>
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
