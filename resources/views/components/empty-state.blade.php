@props([
    'title' => 'Sin información',
    'description' => 'No hay información disponible.',
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state-icon">
        <svg class="icon icon-lg" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
            <path d="M14 2v6h6"></path>
            <path d="M9 15h6"></path>
        </svg>
    </div>

    <h3 class="empty-state-title">
        {{ $title }}
    </h3>

    <p class="empty-state-description">
        {{ $description }}
    </p>

    @if(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>