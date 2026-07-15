@extends('layouts.admin')

@section('title', 'Tambah Admin')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Tambah Admin Baru</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
            @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
            @error('email')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" required minlength="8">
            @error('password')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-input" required minlength="8">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-save mr-2"></i> Simpan Admin Baru</button>
    </form>
</div>
@endsection
