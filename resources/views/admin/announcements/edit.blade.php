@extends('layouts.admin')

@section('title', __('Edit Announcement'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Edit Announcement') }}</h1>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Judul Pengumuman</label>
            <input type="text" name="title" class="form-input" required value="{{ old('title', $announcement->title) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Isi / Konten</label>
            <textarea name="content" class="form-input" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="is_active" class="form-input" required>
                <option value="1" {{ $announcement->is_active ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                <option value="0" {{ !$announcement->is_active ? 'selected' : '' }}>Draft / Sembunyikan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Update Announcement') }}</button>
    </form>
</div>
@endsection
