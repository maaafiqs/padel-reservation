@extends('layouts.admin')

@section('title', __('Add Coach'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Add Coach') }}</h1>
    <a href="{{ route('admin.coaches.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.coaches.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">{{ __('Full Name') }}</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-input" required value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Tarif / Jam (Rp)</label>
            <input type="number" name="price_per_hour" class="form-input" required value="{{ old('price_per_hour', 0) }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Capacity Slot') }}</label>
            <input type="number" name="capacity" class="form-input" required value="{{ old('capacity', 1) }}" min="1">
            <small class="text-muted">Jumlah maksimal orang yang bisa memesan coach ini dalam satu waktu (misal: kelas grup berisi 10 orang).</small>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="is_available" class="form-input" required>
                <option value="1">Aktif / Tersedia</option>
                <option value="0">Non-Aktif</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Bio / Pengalaman</label>
            <textarea name="bio" class="form-input" rows="4">{{ old('bio') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Save Coach') }}</button>
    </form>
</div>
@endsection
