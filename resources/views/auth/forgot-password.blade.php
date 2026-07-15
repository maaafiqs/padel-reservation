@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-container">
    <div class="card" style="max-width: 450px; margin: 4rem auto;">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Lupa Password?</h1>
            <p class="text-muted">Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.</p>
        </div>

        @if (session('status'))
            <div class="card mb-6 bg-primary-light" style="border-left: 4px solid var(--primary);">
                <p class="text-primary font-semibold">{{ session('status') }}</p>
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

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="form-group mb-6">
                <label for="email" class="form-label">Email</label>
                <div class="input-with-icon relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" id="email" class="form-input" style="padding-left: 2.5rem;" required autofocus value="{{ old('email') }}" placeholder="Contoh: budi@gmail.com">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="width: 100%;">
                Kirim Tautan Reset Password
            </button>
        </form>

        <div class="mt-8 text-center border-t pt-6" style="border-color: var(--border);">
            <p class="text-muted">Ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a></p>
        </div>
    </div>
</div>
@endsection
