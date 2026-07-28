@extends('layouts.admin')

@section('title', __('Edit Court'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Edit Court') }}</h1>
    <a href="{{ route('admin.courts.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.courts.update', $court) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">{{ __('Court Name') }}</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name', $court->name) }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Type') }}</label>
            <select name="type" class="form-input" required>
                <option value="Indoor" {{ $court->type === 'Indoor' ? 'selected' : '' }}>Indoor</option>
                <option value="Outdoor" {{ $court->type === 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Price / Hour (Rp)') }}</label>
            <input type="number" name="price_per_hour" class="form-input" required value="{{ old('price_per_hour', $court->price_per_hour) }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-input" required>
                <option value="available" {{ $court->status === 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                <option value="maintenance" {{ $court->status === 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $court->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Update Court') }}</button>
    </form>
</div>
@endsection
