@extends('layouts.admin')

@section('title', 'Tambah Diskon')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">Tambah Diskon</h1>
    <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.discounts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Kode Voucher/Diskon</label>
            <input type="text" name="code" class="form-input" required value="{{ old('code') }}" style="text-transform: uppercase;">
        </div>
        <div class="form-group">
            <label class="form-label">Tipe Diskon</label>
            <select name="type" id="discount-type" class="form-input" required>
                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="nominal" {{ old('type') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
            </select>
        </div>
        <div class="form-group" id="percentage-group">
            <label class="form-label">Persentase Diskon (%)</label>
            <input type="number" name="percentage" id="percentage-input" class="form-input" min="1" max="100" value="{{ old('percentage') }}">
        </div>
        <div class="form-group" id="nominal-group" style="display: none;">
            <label class="form-label">Potongan Harga (Rp)</label>
            <input type="number" name="nominal_amount" id="nominal-input" class="form-input" min="1" value="{{ old('nominal_amount') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Berlaku Sampai Tanggal</label>
            <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until') }}">
            <small class="text-muted">Kosongkan jika berlaku selamanya.</small>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-input" required>
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">Simpan Diskon</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('discount-type');
        const pctGroup = document.getElementById('percentage-group');
        const nomGroup = document.getElementById('nominal-group');
        const pctInput = document.getElementById('percentage-input');
        const nomInput = document.getElementById('nominal-input');

        function toggleInputs() {
            if (typeSelect.value === 'percentage') {
                pctGroup.style.display = 'block';
                nomGroup.style.display = 'none';
                pctInput.setAttribute('required', 'required');
                nomInput.removeAttribute('required');
                nomInput.value = '';
            } else {
                pctGroup.style.display = 'none';
                nomGroup.style.display = 'block';
                nomInput.setAttribute('required', 'required');
                pctInput.removeAttribute('required');
                pctInput.value = '';
            }
        }

        typeSelect.addEventListener('change', toggleInputs);
        toggleInputs(); // Initial check
    });
</script>
@endsection
