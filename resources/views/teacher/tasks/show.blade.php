@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Detalle de tarea')
@section('page-subtitle', $task->title)

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.tasks.index') }}">
        Tareas
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>{{ $task->title }}</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            {{ $task->title }}
        </h1>

        <div class="task-meta">
            <span class="task-meta-item">
                <x-status-badge type="type">
                    {{ $task->type_label }}
                </x-status-badge>
            </span>

            @if($task->isCancelled())
                <x-status-badge type="danger">
                    Cancelada
                </x-status-badge>
            @elseif($task->isExpired())
                <x-status-badge type="neutral">
                    Finalizada
                </x-status-badge>
            @else
                <x-status-badge type="success">
                    Vigente
                </x-status-badge>
            @endif
        </div>
    </div>

    <div class="page-actions">
        @if($task->canBeGraded())
            <a
                href="{{ route('teacher.tasks.grade', $task) }}"
                class="btn btn-primary"
            >
                Evaluar entregas
            </a>
        @endif

        @if(!$task->isCancelled())
            <button
                type="button"
                class="btn btn-danger"
                data-modal-open="cancel-task-modal"
            >
                Cancelar tarea
            </button>
        @endif
    </div>
</div>

<div class="task-summary">
    <div>
        <h2 class="task-summary-title">
            Instrucciones
        </h2>

        <div class="task-description">
            {{ $task->description }}
        </div>
    </div>

    <div class="task-deadline">
        <p class="task-deadline-label">
            Fecha límite
        </p>

        <p class="task-deadline-value">
            {{ $task->due_at->format('d/m/Y') }}
        </p>

        <p class="table-secondary-text">
            Hasta las 23:59
        </p>

        <div class="divider"></div>

        <p class="task-deadline-label">
            Asignada
        </p>

        <p class="task-deadline-value">
            {{ $task->assigned_at->format('d/m/Y H:i') }}
        </p>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Alumnos activos
            </p>
        </div>

        <p class="stat-card-value">
            {{ $activeStudentsCount }}
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Entregadas
            </p>
        </div>

        <p class="stat-card-value">
            {{ $submittedCount }}
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Pendientes
            </p>
        </div>

        <p class="stat-card-value">
            {{ $pendingCount }}
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">
                Avance
            </p>
        </div>

        @php
            $progress = $activeStudentsCount > 0
                ? round(($submittedCount / $activeStudentsCount) * 100)
                : 0;
        @endphp

        <p class="stat-card-value">
            {{ $progress }}%
        </p>

        <div class="progress mt-2">
            <div
                class="progress-bar"
                style="width: {{ min($progress, 100) }}%"
            ></div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-content">
            <h2 class="card-title">
                Estado de entregas
            </h2>

            <p class="card-description">
                Seguimiento individual de los alumnos.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Matrícula</th>
                    <th>Entrega</th>
                    <th>Fecha</th>
                    <th>Calificación</th>
                    <th>Evidencia</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $student)

                    @php
                        $submission = $submissionsByStudent->get($student->id);
                    @endphp

                    <tr>
                        <td>
                            <p class="table-primary-text">
                                {{ $student->name }}
                            </p>

                            @if(!$student->is_active)
                                <p class="table-secondary-text">
                                    Cuenta inactiva
                                </p>
                            @endif
                        </td>

                        <td class="table-mono">
                            {{ $student->enrollment_number }}
                        </td>

                        <td>
                            @if($submission)
                                <x-status-badge type="success">
                                    Entregada
                                </x-status-badge>
                            @elseif($task->isCancelled())
                                <x-status-badge type="neutral">
                                    Cancelada
                                </x-status-badge>
                            @elseif($task->isExpired())
                                <x-status-badge type="danger">
                                    No entregada
                                </x-status-badge>
                            @else
                                <x-status-badge type="warning">
                                    Pendiente
                                </x-status-badge>
                            @endif
                        </td>

                        <td class="table-mono">
                            @if($submission)
                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($submission?->grade?->isPublished())
                                <span class="grade-value">
                                    <span class="grade-value-number">
                                        {{ number_format((float) $submission->grade->score, 1) }}
                                    </span>

                                    <span class="grade-value-total">
                                        /10
                                    </span>
                                </span>
                            @elseif($submission?->grade?->isDraft())
                                <x-status-badge type="warning">
                                    Borrador
                                </x-status-badge>
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($submission)
                                <div class="table-actions">
                                    <a
                                        href="{{ route('teacher.submissions.view', $submission) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Ver PDF
                                    </a>

                                    <a
                                        href="{{ route('teacher.submissions.download', $submission) }}"
                                        class="btn btn-ghost btn-sm"
                                    >
                                        Descargar
                                    </a>
                                </div>
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(!$task->isCancelled())
    <x-modal
        id="cancel-task-modal"
        title="Cancelar tarea"
    >
        <p class="text-secondary mb-0">
            La tarea dejará de aceptar evidencias y los alumnos
            recibirán una notificación de cancelación.
        </p>

        <x-slot:footer>
            <button
                type="button"
                class="btn btn-secondary"
                data-modal-close
            >
                Volver
            </button>

            <form
                method="POST"
                action="{{ route('teacher.tasks.cancel', $task) }}"
                data-prevent-double-submit
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Confirmar cancelación
                </button>
            </form>
        </x-slot:footer>
    </x-modal>
@endif

@endsection