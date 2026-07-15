@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Tambah Pengumuman</h1>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.announcements.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Judul Pengumuman</label>
            <input type="text" name="title" class="form-input" required value="{{ old('title') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Isi / Konten</label>
            <textarea name="content" class="form-input" rows="6" required>{{ old('content') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-input" required>
                <option value="1">Aktif (Tampilkan)</option>
                <option value="0">Draft / Sembunyikan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">Simpan Pengumuman</button>
    </form>
</div>
@endsection
