@extends('layouts.admin')

@section('title', __('Manage Inventory'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Manage Inventory') }}</h1>
    <a href="{{ route('admin.inventories.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i> {{ __('Add Item') }}</a>
</div>

<div class="card mb-6" style="background-color: var(--background); border: 1px solid var(--border); box-shadow: none;">
    <form action="{{ route('admin.inventories.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="form-group mb-0" style="flex: 1; min-width: 250px;">
            <label class="form-label text-sm">Cari (Nama atau Kode Barang)</label>
            <input type="text" name="search" class="form-input" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
        </div>
        <div class="form-group mb-0" style="width: 200px;">
            <label class="form-label text-sm">Jenis Barang</label>
            <select name="type" class="form-input">
                <option value="">Semua Jenis</option>
                <option value="rental" {{ request('type') == 'rental' ? 'selected' : '' }}>Barang Sewa</option>
                <option value="consumable" {{ request('type') == 'consumable' ? 'selected' : '' }}>Konsumsi / Habis Pakai</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass mr-2"></i> Cari</button>
            @if(request('search') || request('type'))
                <a href="{{ route('admin.inventories.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kode Barang</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Item Name') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Rental Price') }}</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Stok Tersedia</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;">{{ $inventory->item_code ?? '-' }}</td>
                    <td style="padding: 1rem; font-weight: 500;">
                        {{ $inventory->name }}
                        @if($inventory->is_consumable)
                            <span class="badge badge-warning" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; margin-left: 0.5rem;" title="Barang Habis Pakai (Stok tidak dikembalikan saat selesai)"><i class="fa-solid fa-bottle-water"></i> Konsumsi</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">Rp {{ number_format($inventory->price, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        <span class="badge {{ $inventory->stock > 5 ? 'badge-success' : 'badge-warning' }}">{{ $inventory->stock }}</span>
                    </td>
                    <td style="padding: 1rem;">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.inventories.qrcode', $inventory) }}" target="_blank" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" title="Cetak QR Code"><i class="fa-solid fa-qrcode"></i></a>
                            <a href="{{ route('admin.inventories.edit', $inventory) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" title="{{ __('Edit') }}"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.inventories.destroy', $inventory) }}" method="POST" onsubmit="return confirm('{{ __('Delete this item?') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fee2e2; color: #991b1b;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 1rem; text-align: center; color: var(--text-muted);">{{ __('No inventory data yet.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
