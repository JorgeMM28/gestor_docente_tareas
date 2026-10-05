@props([
    'id',
    'title' => 'Confirmar acción',
])

<div
    id="{{ $id }}"
    class="modal"
    data-modal
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
>
    <div class="modal-dialog">
        <div class="modal-header">
            <h2
                class="modal-title"
                data-modal-title
            >
                {{ $title }}
            </h2>

            <button
                type="button"
                class="modal-close"
                data-modal-close
                aria-label="Cerrar"
            >
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="modal-footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>