@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general del grupo')

@section('content')

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Panel del docente
        </h1>

        <p class="page-description">
            Consulta rápidamente el estado de los alumnos,
            tareas y entregas del grupo.
        </p>
    </div>

    <div class="page-actions">
        <a
            href="{{ route('teacher.tasks.create') }}"
            class="btn btn-primary"
        >
            <svg class="icon icon-sm" viewBox="0 0 24 24">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
            </svg>

            Nueva tarea
        </a>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Alumnos activos
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $totalStudents }}
        </p>

        <p class="stat-card-note">
            Actualmente habilitados
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Tareas
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                    <path d="M14 2v6h6"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $totalTasks }}
        </p>

        <p class="stat-card-note">
            Tareas no canceladas
        </p>
    </div>

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
            Aún aceptan entregas
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Evidencias
            </p>

            <div class="stat-card-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M21.44 11.05 12.25 20.24a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                </svg>
            </div>
        </div>

        <p class="stat-card-value">
            {{ $totalSubmissions }}
        </p>

        <p class="stat-card-note">
            Archivos entregados
        </p>
    </div>

</div>

<div class="dashboard-grid">

    <section class="card">
        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Tareas recientes
                </h2>

                <p class="card-description">
                    Últimas tareas asignadas al grupo.
                </p>
            </div>

            <a
                href="{{ route('teacher.tasks.index') }}"
                class="btn btn-secondary btn-sm"
            >
                Ver todas
            </a>
        </div>

        @if($recentTasks->isEmpty())
            <x-empty-state
                title="No hay tareas"
                description="Todavía no has creado ninguna tarea."
            >
                <x-slot:action>
                    <a
                        href="{{ route('teacher.tasks.create') }}"
                        class="btn btn-primary"
                    >
                        Crear primera tarea
                    </a>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Tipo</th>
                            <th>Entrega</th>
                            <th>Evidencias</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recentTasks as $task)
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

                                <td class="table-mono">
                                    {{ $task->due_at->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $task->submissions_count }}
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a
                                            href="{{ route('teacher.tasks.show', $task) }}"
                                            class="btn btn-secondary btn-sm"
                                        >
                                            Ver
                                        </a>
                                    </div>
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
                    Actividad reciente
                </h2>

                <p class="card-description">
                    Últimas evidencias recibidas.
                </p>
            </div>
        </div>

        @if($recentSubmissions->isEmpty())
            <x-empty-state
                title="Sin entregas"
                description="Aún no se han recibido evidencias."
            />
        @else
            <div class="notification-list">
                @foreach($recentSubmissions as $submission)
                    <a
                        href="{{ route('teacher.tasks.show', $submission->task_id) }}"
                        class="notification-item"
                    >
                        <div class="notification-icon">
                            <svg class="icon" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                <path d="M14 2v6h6"></path>
                                <path d="m9 15 2 2 4-4"></path>
                            </svg>
                        </div>

                        <div class="notification-content">
                            <p class="notification-title">
                                {{ $submission->student->name }}
                            </p>

                            <p class="notification-message">
                                Entregó {{ $submission->task->title }}
                            </p>

                            <span class="notification-time">
                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</div>

@endsection