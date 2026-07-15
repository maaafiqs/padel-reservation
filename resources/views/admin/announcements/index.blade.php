@extends('layouts.admin')

@section('title', 'Kelola Pengumuman')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Kelola Pengumuman</h1>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> Tambah Pengumuman</a>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Judul</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tanggal</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Status</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;">{{ $announcement->title }}</td>
                    <td style="padding: 1rem;">{{ $announcement->created_at->format('d M Y') }}</td>
                    <td style="padding: 1rem;">
                        @if($announcement->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 1rem; text-align: center; color: var(--text-muted);">Belum ada data pengumuman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
