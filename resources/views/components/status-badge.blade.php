@props(['status'])

@php
    $status = strtolower($status ?? '');
    $config = match($status) {
        'confirmed', 'available', 'active' => [
            'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'icon' => 'fa-check-circle',
            'label' => __('Confirmed')
        ],
        'pending' => [
            'class' => 'bg-amber-100 text-amber-800 border-amber-200',
            'icon' => 'fa-clock',
            'label' => __('Pending')
        ],
        'completed' => [
            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
            'icon' => 'fa-flag-checkered',
            'label' => __('Completed')
        ],
        'cancelled', 'rejected', 'maintenance' => [
            'class' => 'bg-rose-100 text-rose-800 border-rose-200',
            'icon' => 'fa-times-circle',
            'label' => __('Cancelled')
        ],
        default => [
            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
            'icon' => 'fa-info-circle',
            'label' => ucfirst($status)
        ]
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {$config['class']}"]) }}>
    <i class="fas {{ $config['icon'] }}"></i>
    <span>{{ $slot->isEmpty() ? $config['label'] : $slot }}</span>
</span>
