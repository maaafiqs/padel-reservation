@extends('layouts.admin')

@section('title', 'Edit Coach')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Edit Coach</h1>
    <a href="{{ route('admin.coaches.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.coaches.update', $coach) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name', $coach->name) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-input" required value="{{ old('phone', $coach->phone) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Tarif / Jam (Rp)</label>
            <input type="number" name="price_per_hour" class="form-input" required value="{{ old('price_per_hour', $coach->price_per_hour) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Slot Kapasitas</label>
            <input type="number" name="capacity" class="form-input" required value="{{ old('capacity', $coach->capacity) }}" min="1">
            <small class="text-muted">Jumlah maksimal orang yang bisa memesan coach ini dalam satu waktu.</small>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="is_available" class="form-input" required>
                <option value="1" {{ $coach->is_available ? 'selected' : '' }}>Aktif / Tersedia</option>
                <option value="0" {{ !$coach->is_available ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Bio / Pengalaman</label>
            <textarea name="bio" class="form-input" rows="4">{{ old('bio', $coach->bio) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">Perbarui Coach</button>
    </form>
</div>
@endsection
