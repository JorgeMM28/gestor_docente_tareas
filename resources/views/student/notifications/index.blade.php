@extends('layouts.app')

@section('title', 'Notificaciones')
@section('page-title', 'Notificaciones')
@section('page-subtitle', 'Avisos del sistema')

@section('content')

<div class="page-header">

    <div class="page-header-content">

        <h1 class="page-title">
            Notificaciones
        </h1>

        <p class="page-description">
            Consulta los avisos relacionados con tus tareas
            y calificaciones.
        </p>

    </div>

    @if($unreadCount > 0)

        <div class="page-actions">

            <form
                method="POST"
                action="{{ route('student.notifications.read-all') }}"
                data-prevent-double-submit
            >

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="btn btn-secondary"
                >
                    <svg
                        class="icon icon-sm"
                        viewBox="0 0 24 24"
                    >
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>

                    Marcar todas como leídas
                </button>

            </form>

        </div>

    @endif

</div>

<div class="card">

    @if($notifications->isEmpty())

        <x-empty-state
            title="Sin notificaciones"
            description="Todavía no tienes avisos en el sistema."
        />

    @else

        <div class="notification-list">

            @foreach($notifications as $notification)

                <div
                    class="notification-item {{ $notification->read_at ? '' : 'notification-unread' }}"
                >

                    <div class="notification-icon">

                        @if($notification->type === 'task_created')

                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                <path d="M14 2v6h6"></path>
                                <path d="M12 11v6"></path>
                                <path d="M9 14h6"></path>
                            </svg>

                        @elseif($notification->type === 'task_cancelled')

                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="m9 9 6 6"></path>
                                <path d="m15 9-6 6"></path>
                            </svg>

                        @elseif($notification->type === 'grade_published')

                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12 2 15 8l6 .9-4.5 4.4 1.1 6.2L12 16.6l-5.6 2.9 1.1-6.2L3 8.9 9 8Z"></path>
                            </svg>

                        @else

                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v4"></path>
                                <path d="M12 16h.01"></path>
                            </svg>

                        @endif

                    </div>

                    <div class="notification-content">

                        <p class="notification-title">
                            {{ $notification->title }}
                        </p>

                        <p class="notification-message">
                            {{ $notification->message }}
                        </p>

                        <span class="notification-time">
                            {{ $notification->created_at->format('d/m/Y H:i') }}
                        </span>

                    </div>

                    <div class="notification-actions">

                        @if($notification->task_id)

                            <a
                                href="{{ route('student.notifications.open', $notification) }}"
                                class="btn btn-secondary btn-sm"
                            >
                                Ver tarea
                            </a>

                        @elseif(!$notification->read_at)

                            <form
                                method="POST"
                                action="{{ route('student.notifications.read', $notification) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="btn btn-ghost btn-sm"
                                >
                                    Marcar como leída
                                </button>

                            </form>

                        @endif

                        @if(!$notification->read_at)

                            <span
                                class="notification-dot"
                                title="Sin leer"
                            ></span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

        <div class="table-footer">

            <p class="table-count">
                {{ $notifications->total() }}
                notificación{{ $notifications->total() === 1 ? '' : 'es' }}
            </p>

            @if($notifications->hasPages())
                {{ $notifications->links() }}
            @endif

        </div>

    @endif

</div>

@endsection