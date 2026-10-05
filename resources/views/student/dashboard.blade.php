@extends('layouts.app')

@section('title', 'Inicio')
@section('page-title', 'Inicio')
@section('page-subtitle', 'Resumen de tus actividades')

@section('content')

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Hola, {{ auth()->user()->name }}
        </h1>

        <p class="page-description">
            Consulta tus tareas pendientes, entregas y calificaciones.
        </p>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Tareas vigentes
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 7v5l3 2"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $activeTasks }}
        </p>

        <p class="stat-card-note">
            Actualmente disponibles
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Pendientes
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M12 12v4"></path>
                    <path d="M12 8h.01"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $pendingTasks }}
        </p>

        <p class="stat-card-note">
            Aún no has entregado
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Entregadas
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $submittedTasks }}
        </p>

        <p class="stat-card-note">
            Evidencias enviadas
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Calificadas
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M12 2 15 8l6 .9-4.5 4.4 1.1 6.2L12 16.6l-5.6 2.9 1.1-6.2L3 8.9 9 8Z"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $gradedTasks }}
        </p>

        <p class="stat-card-note">
            Calificaciones publicadas
        </p>
    </div>

</div>

<div class="dashboard-grid">

    <section class="card">
        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Próximas tareas
                </h2>

                <p class="card-description">
                    Actividades vigentes ordenadas por fecha límite.
                </p>
            </div>

            <a
                href="{{ route('student.tasks.index') }}"
                class="btn btn-secondary btn-sm"
            >
                Ver todas
            </a>
        </div>

        @if($upcomingTasks->isEmpty())

            <x-empty-state
                title="Sin tareas pendientes"
                description="No tienes actividades pendientes por entregar."
            />

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Tipo</th>
                            <th>Entrega</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($upcomingTasks as $task)

                            @php
                                $submission = $task->submissions->first();
                            @endphp

                            <tr>
                                <td>
                                    <p class="table-primary-text">
                                        {{ $task->title }}
                                    </p>

                                    <p class="table-secondary-text">
                                        Asignada
                                        {{ $task->assigned_at->format('d/m/Y') }}
                                    </p>
                                </td>

                                <td>
                                    <x-status-badge type="type">
                                        {{ $task->type_label }}
                                    </x-status-badge>
                                </td>

                                <td>
                                    <p class="table-primary-text table-mono">
                                        {{ $task->due_at->format('d/m/Y') }}
                                    </p>

                                    <p class="table-secondary-text">
                                        23:59
                                    </p>
                                </td>

                                <td>
                                    @if($submission)
                                        <x-status-badge type="success">
                                            Entregada
                                        </x-status-badge>
                                    @else
                                        <x-status-badge type="warning">
                                            Pendiente
                                        </x-status-badge>
                                    @endif
                                </td>

                                <td>
                                    <a
                                        href="{{ route('student.tasks.show', $task) }}"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Ver tarea
                                    </a>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </section>

    <section class="card">

        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Notificaciones recientes
                </h2>

                <p class="card-description">
                    Últimos avisos de tus actividades.
                </p>
            </div>

            <a
                href="{{ route('student.notifications.index') }}"
                class="btn btn-secondary btn-sm"
            >
                Ver todas
            </a>
        </div>

        @if($recentNotifications->isEmpty())

            <x-empty-state
                title="Sin notificaciones"
                description="No tienes notificaciones recientes."
            />

        @else

            <div class="notification-list">

                @foreach($recentNotifications as $notification)

                    <a
                        href="{{ $notification->task_id
                            ? route('student.tasks.show', $notification->task_id)
                            : route('student.notifications.index') }}"
                        class="notification-item {{ $notification->read_at ? '' : 'notification-unread' }}"
                    >
                        <div class="notification-icon">

                            @if($notification->type === 'task_created')

                                <svg class="icon" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                    <path d="M14 2v6h6"></path>
                                    <path d="M12 11v6"></path>
                                    <path d="M9 14h6"></path>
                                </svg>

                            @elseif($notification->type === 'task_cancelled')

                                <svg class="icon" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="m9 9 6 6"></path>
                                    <path d="m15 9-6 6"></path>
                                </svg>

                            @else

                                <svg class="icon" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"></path>
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

                        @if(!$notification->read_at)
                            <span
                                class="notification-dot"
                                title="Sin leer"
                            ></span>
                        @endif
                    </a>

                @endforeach

            </div>

        @endif

    </section>

</div>

@endsection