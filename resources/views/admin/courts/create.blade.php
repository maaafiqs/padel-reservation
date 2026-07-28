@extends('layouts.admin')

@section('title', __('Add Court'))

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl">{{ __('Add Court') }}</h1>
    <a href="{{ route('admin.courts.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left mr-2"></i> {{ __('Back') }}</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.courts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">{{ __('Court Name') }}</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Type') }}</label>
            <select name="type" class="form-input" required>
                <option value="Indoor">Indoor</option>
                <option value="Outdoor">Outdoor</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Price / Hour (Rp)') }}</label>
            <input type="number" name="price_per_hour" class="form-input" required value="{{ old('price_per_hour', 0) }}">
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-input" required>
                <option value="available">{{ __('Available') }}</option>
                <option value="maintenance">{{ __('Maintenance') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width: 100%;">{{ __('Save Court') }}</button>
    </form>
</div>
@endsection
