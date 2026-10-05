@props([
    'type' => 'neutral',
])

@php
    $class = match ($type) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
        'type' => 'badge-type',
        default => 'badge-neutral',
    };
@endphp

<span {{ $attributes->merge([
    'class' => 'badge ' . $class,
]) }}>
    {{ $slot }}
</span>