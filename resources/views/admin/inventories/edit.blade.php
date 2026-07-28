@extends('layouts.admin')

@section('title', __('Edit Item'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Edit Item') }}</h1>
    <a href="{{ route('admin.inventories.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.inventories.update', $inventory) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="item_code" class="form-input" required value="{{ old('item_code', $inventory->item_code) }}">
            @error('item_code')<span class="text-danger text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Item Name') }}</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name', $inventory->name) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Harga Sewa / Pcs (Rp)</label>
            <input type="number" name="price" class="form-input" required value="{{ old('price', $inventory->price) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-input" required value="{{ old('stock', $inventory->stock) }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $inventory->description) }}</textarea>
        </div>
        <div class="form-group mb-6">
            <label class="form-label flex items-center gap-2 cursor-pointer p-3 border rounded-md" style="background-color: var(--background); border-color: var(--border);">
                <input type="checkbox" name="is_consumable" value="1" {{ old('is_consumable', $inventory->is_consumable) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary" style="width: 1.25rem; height: 1.25rem;">
                <div>
                    <span class="text-sm font-semibold text-gray-800">Barang Habis Pakai / Konsumsi</span>
                    <p class="text-xs text-muted mt-1">Centang jika ini adalah barang yang dibeli/dikonsumsi (seperti air minum/bola) yang stoknya TIDAK kembali setelah selesai bermain.</p>
                </div>
            </label>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Update Item') }}</button>
    </form>
</div>
@endsection
