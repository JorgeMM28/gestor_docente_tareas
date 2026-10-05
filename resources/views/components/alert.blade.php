@props([
    'type' => 'info',
    'title' => null,
    'persistent' => false,
])

@php
    $alertClass = match ($type) {
        'success' => 'alert-success',
        'error', 'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info',
    };
@endphp

<div
    {{ $attributes->merge([
        'class' => 'alert ' . $alertClass,
    ]) }}
    data-alert
    @if($persistent)
        data-alert-persistent="true"
    @endif
>
    <div class="alert-icon">
        @if($type === 'success')
            <svg class="icon" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>
        @elseif($type === 'error' || $type === 'danger')
            <svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M15 9l-6 6"></path>
                <path d="M9 9l6 6"></path>
            </svg>
        @elseif($type === 'warning')
            <svg class="icon" viewBox="0 0 24 24">
                <path d="M10.3 2.9 1.8 17a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 2.9a2 2 0 0 0-3.4 0Z"></path>
                <path d="M12 9v4"></path>
                <path d="M12 17h.01"></path>
            </svg>
        @else
            <svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
        @endif
    </div>

    <div class="alert-content">
        @if($title)
            <p class="alert-title">
                {{ $title }}
            </p>
        @endif

        <div class="alert-message">
            {{ $slot }}
        </div>
    </div>

    <button
        type="button"
        class="alert-close"
        data-alert-close
        aria-label="Cerrar mensaje"
    >
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
    </button>
</div>