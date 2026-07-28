@extends('layouts.admin')

@section('title', __('Manage Reservations'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Manage Reservations') }}</h1>
</div>

<div class="card mb-6" style="background-color: var(--background); border: 1px solid var(--border); box-shadow: none;">
    <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="form-group mb-0" style="flex: 1; min-width: 250px;">
            <label class="form-label text-sm">{{ __('Search (ID, Username, Court)') }}</label>
            <input type="text" name="search" class="form-input" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
        </div>
        <div class="form-group mb-0" style="width: 200px;">
            <label class="form-label text-sm">Status Reservasi</label>
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass mr-2"></i> Cari</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card p-0 mb-8" style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; white-space: nowrap;">
            <thead style="background-color: var(--primary-light);">
                <tr>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">ID Booking</th>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('User') }}</th>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Court Details & Time') }}</th>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Total Price') }}</th>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text);">{{ __('Status') }}</th>
                    <th style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--text); text-align: center;">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                <tr style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 1rem 1.5rem;">
                        <span class="text-primary font-bold">{{ $res->reservation_code ?? '#RES-' . str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td style="padding: 1rem 1.5rem;">
                        <div style="font-weight: 500; color: var(--text);">{{ $res->user ? $res->user->name : 'User Terhapus' }}</div>
                        @if($res->user)
                            <div class="text-muted text-xs mt-1">{{ $res->user->email }}</div>
                        @endif
                    </td>
                    <td style="padding: 1rem 1.5rem;">
                        <div style="font-weight: 500;">{{ $res->court ? $res->court->name : 'Lapangan Terhapus' }}</div>
                        <div class="text-muted text-sm mt-1">
                            <i class="fa-regular fa-calendar text-xs"></i> {{ \Carbon\Carbon::parse($res->reservation_date)->translatedFormat('d M Y') }} &bull; 
                            <i class="fa-regular fa-clock text-xs"></i> {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                        </div>
                    </td>
                    <td style="padding: 1rem 1.5rem; font-weight: 500;">Rp {{ number_format($res->final_price, 0, ',', '.') }}</td>
                    <td style="padding: 1rem 1.5rem;">
                        @if($res->status === 'pending')
                            @if($res->payment_proof)
                                <span class="badge badge-warning" style="padding: 0.35rem 0.75rem;">{{ __('Awaiting Verification') }}</span>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $res->payment_proof) }}" target="_blank" class="text-xs text-primary hover:underline font-semibold" style="display: inline-flex; align-items: center; gap: 0.25rem;"><i class="fa-solid fa-image"></i> {{ __('View Proof') }}</a>
                                </div>
                            @else
                                <span class="badge badge-warning" style="padding: 0.35rem 0.75rem; opacity: 0.8;">{{ __('Awaiting Payment') }}</span>
                            @endif
                        @elseif($res->status === 'confirmed')
                            <span class="badge badge-success" style="padding: 0.35rem 0.75rem;">{{ __('Confirmed') }}</span>
                        @elseif($res->status === 'completed')
                            <span class="badge badge-primary" style="padding: 0.35rem 0.75rem; background-color: #0284c7; color: white;">{{ __('Completed') }}</span>
                        @else
                            <span class="badge" style="padding: 0.35rem 0.75rem; background-color: #fee2e2; color: #991b1b;">{{ __('Cancelled') }}</span>
                        @endif
                    </td>
                    <td style="padding: 1rem 1.5rem;">
                        <div class="flex justify-center gap-2">
                            @if($res->status === 'pending' && $res->payment_proof)
                                <form action="{{ route('admin.reservations.update', $res) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button class="btn btn-primary" style="padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.875rem;" title="{{ __('Confirm Payment') }}"><i class="fa-solid fa-check"></i></button>
                                </form>
                            @endif
                            @if($res->status === 'confirmed')
                                <form action="{{ route('admin.reservations.update', $res) }}" method="POST" onsubmit="return confirm('Tandai reservasi ini telah selesai bermain? Barang akan otomatis dikembalikan ke stok.');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn" style="padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.875rem; background-color: #0ea5e9; color: white; border: 1px solid #0284c7;" title="Tandai Selesai"><i class="fa-solid fa-flag-checkered"></i></button>
                                </form>
                            @endif
                            <a href="{{ route('admin.reservations.edit', $res) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.875rem;" title="Edit Reservasi"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.reservations.destroy', $res) }}" method="POST" onsubmit="return confirm('Hapus reservasi ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171;" title="{{ __('Delete') }}"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 3rem 1rem; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-gray-300"></i>
                        <p>Belum ada data reservasi yang ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
