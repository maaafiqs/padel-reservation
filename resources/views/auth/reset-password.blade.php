@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="auth-container">
    <div class="card" style="max-width: 450px; margin: 4rem auto;">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Reset Password</h1>
            <p class="text-muted">Silakan masukkan kata sandi baru Anda.</p>
        </div>

        @if($errors->any())
            <div class="card mb-6" style="background-color: #fee2e2; border-left: 4px solid #991b1b;">
                <ul style="color: #991b1b; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email</label>
                <div class="input-with-icon relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" id="email" class="form-input" style="padding-left: 2.5rem;" required value="{{ old('email', $email) }}" readonly>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Password Baru</label>
                <div class="input-with-icon relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password" id="password" class="form-input" style="padding-left: 2.5rem;" required autofocus placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div class="form-group mb-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <div class="input-with-icon relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" style="padding-left: 2.5rem;" required placeholder="Ketik ulang password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="width: 100%;">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
