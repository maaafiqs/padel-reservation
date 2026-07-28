@extends('layouts.admin')

@section('title', __('Edit Discount'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Edit Discount') }}</h1>
    <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Kode Voucher/Diskon</label>
            <input type="text" name="code" class="form-input" required value="{{ old('code', $discount->code) }}" style="text-transform: uppercase;">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Discount Type') }}</label>
            <select name="type" id="discount-type" class="form-input" required>
                <option value="percentage" {{ old('type', $discount->type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="nominal" {{ old('type', $discount->type ?? 'percentage') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
            </select>
        </div>
        <div class="form-group" id="percentage-group">
            <label class="form-label">Persentase Diskon (%)</label>
            <input type="number" name="percentage" id="percentage-input" class="form-input" min="1" max="100" value="{{ old('percentage', $discount->percentage) }}">
        </div>
        <div class="form-group" id="nominal-group" style="display: none;">
            <label class="form-label">Potongan Harga (Rp)</label>
            <input type="number" name="nominal_amount" id="nominal-input" class="form-input" min="1" value="{{ old('nominal_amount', $discount->nominal_amount ? (int)$discount->nominal_amount : '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Berlaku Sampai Tanggal</label>
            <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until', $discount->valid_until ? \Carbon\Carbon::parse($discount->valid_until)->format('Y-m-d') : '') }}">
            <small class="text-muted">Kosongkan jika berlaku selamanya.</small>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="is_active" class="form-input" required>
                <option value="1" {{ $discount->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !$discount->is_active ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Update Discount') }}</button>
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
            } else {
                pctGroup.style.display = 'none';
                nomGroup.style.display = 'block';
                nomInput.setAttribute('required', 'required');
                pctInput.removeAttribute('required');
            }
        }

        typeSelect.addEventListener('change', toggleInputs);
        toggleInputs(); // Initial check
    });
</script>
@endsection
