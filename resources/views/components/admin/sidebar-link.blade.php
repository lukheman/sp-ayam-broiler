@props([
    'href' => '#',
    'icon' => 'fas fa-circle',
    'active' => null
])

@php
    // Auto-detect active state based on current URL if not explicitly set
    $isActive = $active ?? request()->url() === $href;
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $isActive ? 'active' : '']) }}>
    <i class="{{ $icon }}"></i>
    <span>{{ $slot }}</span>
</a>
