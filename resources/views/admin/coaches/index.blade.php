@extends('layouts.admin')

@section('title', 'Kelola Coach')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Kelola Coach</h1>
    <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> Tambah Coach</a>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Nama Coach</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">No. Telp</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tarif/Jam</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Slot Kapasitas</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Status</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coaches as $coach)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;">{{ $coach->name }}</td>
                    <td style="padding: 1rem;">{{ $coach->phone }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($coach->price_per_hour, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">{{ $coach->capacity }} Orang</td>
                    <td style="padding: 1rem;">
                        @if($coach->is_available)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.coaches.edit', $coach) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" onsubmit="return confirm('Hapus coach ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 1rem; text-align: center; color: var(--text-muted);">Belum ada data coach.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
