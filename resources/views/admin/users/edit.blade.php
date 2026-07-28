@extends('layouts.admin')

@section('title', 'Edit Role Pengguna')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Edit Role Pengguna</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">{{ __('Full Name') }}</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
            @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">{{ __('Email Address') }}</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
            @error('email')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>
        
        <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0;">
        <p class="text-muted text-sm mb-4">Kosongkan kolom password di bawah ini jika tidak ingin mengubah password.</p>

        <div class="form-group">
            <label class="form-label">Password Baru (Opsional)</label>
            <input type="password" name="password" class="form-input" minlength="8">
            @error('password')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('Confirm New Password') }}</label>
            <input type="password" name="password_confirmation" class="form-input" minlength="8">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-save mr-2"></i> Perbarui Data Admin</button>
    </form>
</div>
@endsection
