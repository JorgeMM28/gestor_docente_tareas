@extends('layouts.app')

@section('title', 'Evaluar tarea')
@section('page-title', 'Evaluar tarea')
@section('page-subtitle', $task->title)

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.tasks.index') }}">
        Tareas
    </a>

    <span class="breadcrumb-separator">/</span>

    <a href="{{ route('teacher.tasks.show', $task) }}">
        {{ $task->title }}
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>Evaluar</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Evaluar entregas
        </h1>

        <p class="page-description">
            Asigna una calificación de 0 a 10 a las evidencias recibidas.
        </p>
    </div>

    <div class="page-actions">
        <form
            method="POST"
            action="{{ route('teacher.tasks.grades.publish-all', $task) }}"
            data-prevent-double-submit
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="btn btn-primary"
                data-confirm="¿Deseas publicar todas las calificaciones guardadas como borrador?"
            >
                Publicar borradores
            </button>
        </form>
    </div>
</div>

<x-alert
    type="info"
    title="Publicación de calificaciones"
    persistent
>
    Las calificaciones guardadas como borrador no son visibles
    para los alumnos. Al publicarlas, el alumno podrá consultarlas
    y recibirá una notificación.
</x-alert>

<div class="table-container">

    @if($task->submissions->isEmpty())
        <x-empty-state
            title="No hay entregas"
            description="Ningún alumno entregó evidencia para esta tarea."
        />
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Matrícula</th>
                        <th>Evidencia</th>
                        <th>Calificación</th>
                        <th>Estado</th>
                        <th>Guardar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($task->submissions as $submission)
                        <tr>
                            <td>
                                <p class="table-primary-text">
                                    {{ $submission->student->name }}
                                </p>

                                <p class="table-secondary-text">
                                    Entregó
                                    {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                </p>
                            </td>

                            <td class="table-mono">
                                {{ $submission->student->enrollment_number }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('teacher.submissions.view', $submission) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Ver PDF
                                </a>
                            </td>

                            <td colspan="3">
                                <form
                                    method="POST"
                                    action="{{ route('teacher.tasks.grades.update', $task) }}"
                                    class="flex items-center gap-1"
                                    data-prevent-double-submit
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="submission_id"
                                        value="{{ $submission->id }}"
                                    >

                                    <input
                                        type="number"
                                        name="score"
                                        class="form-control grade-input"
                                        min="0"
                                        max="10"
                                        step="0.01"
                                        value="{{ old(
                                            'submission_id'
                                        ) == $submission->id
                                            ? old('score')
                                            : ($submission->grade
                                                ? number_format(
                                                    (float) $submission->grade->score,
                                                    2,
                                                    '.',
                                                    ''
                                                )
                                                : '')
                                        }}"
                                        data-score-input
                                        required
                                    >

                                    <span class="text-muted">
                                        / 10
                                    </span>

                                    <div style="min-width: 95px;">
                                        @if($submission->grade?->isPublished())
                                            <x-status-badge type="success">
                                                Publicada
                                            </x-status-badge>
                                        @elseif($submission->grade?->isDraft())
                                            <x-status-badge type="warning">
                                                Borrador
                                            </x-status-badge>
                                        @else
                                            <x-status-badge type="neutral">
                                                Sin evaluar
                                            </x-status-badge>
                                        @endif
                                    </div>

                                    <button
                                        type="submit"
                                        name="action"
                                        value="draft"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Guardar borrador
                                    </button>

                                    <button
                                        type="submit"
                                        name="action"
                                        value="publish"
                                        class="btn btn-primary btn-sm"
                                    >
                                        Publicar
                                    </button>
                                </form>

                                @if(
                                    old('submission_id') == $submission->id
                                    && $errors->any()
                                )
                                    <div class="form-error mt-1">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

<div class="mt-3">
    <a
        href="{{ route('teacher.tasks.show', $task) }}"
        class="btn btn-secondary"
    >
        Volver a la tarea
    </a>
</div>

@endsection