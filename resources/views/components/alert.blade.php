@props(['type' => 'info', 'message' => null])

@php
    $styles = match($type) {
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'danger', 'error' => 'bg-rose-50 text-rose-800 border-rose-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        default => 'bg-sky-50 text-sky-800 border-sky-200',
    };
    $icon = match($type) {
        'success' => 'fa-check-circle',
        'danger', 'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        default => 'fa-info-circle',
    };
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-xl border flex items-start gap-3 {$styles}"]) }} role="alert">
    <i class="fas {{ $icon }} mt-0.5 text-lg"></i>
    <div class="flex-1 text-sm font-medium">
        {{ $message ?? $slot }}
    </div>
</div>
