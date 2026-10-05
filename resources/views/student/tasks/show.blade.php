@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Detalle de tarea')
@section('page-subtitle', $task->title)

@section('content')

@php
    $submission = $task->submissions->first();

    $publishedGrade =
        $submission?->grade?->isPublished()
            ? $submission->grade
            : null;
@endphp

<div class="breadcrumbs">

    <a href="{{ route('student.tasks.index') }}">
        Mis tareas
    </a>

    <span class="breadcrumb-separator">
        /
    </span>

    <span>
        {{ $task->title }}
    </span>

</div>

<div class="page-header">

    <div class="page-header-content">

        <h1 class="page-title">
            {{ $task->title }}
        </h1>

        <div class="task-meta">

            <x-status-badge type="type">
                {{ $task->type_label }}
            </x-status-badge>

            @if($task->isCancelled())

                <x-status-badge type="danger">
                    Cancelada
                </x-status-badge>

            @elseif($submission)

                <x-status-badge type="success">
                    Entregada
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

        </div>

    </div>

</div>

@if($task->isCancelled())

    <x-alert
        type="warning"
        title="Tarea cancelada"
        persistent
    >
        Esta actividad fue cancelada por el docente.
        Ya no es posible enviar o modificar evidencias.
    </x-alert>

@elseif($task->isExpired() && !$submission)

    <x-alert
        type="error"
        title="Periodo de entrega finalizado"
        persistent
    >
        La fecha límite de esta tarea ya terminó y no se
        registró una evidencia.
    </x-alert>

@endif

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
            Fecha de asignación
        </p>

        <p class="task-deadline-value">
            {{ $task->assigned_at->format('d/m/Y') }}
        </p>

        <p class="table-secondary-text">
            {{ $task->assigned_at->format('H:i') }}
        </p>

    </div>

</div>

<div class="dashboard-grid">

    <section class="card">

        <div class="card-header">

            <div class="card-header-content">

                <h2 class="card-title">
                    Mi evidencia
                </h2>

                <p class="card-description">
                    Archivo PDF correspondiente a esta actividad.
                </p>

            </div>

        </div>

        @if($submission)

            <div class="card-body">

                <div class="file-selected">

                    <div class="file-selected-icon">

                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                        >
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                            <path d="M14 2v6h6"></path>
                            <path d="M9 13h6"></path>
                            <path d="M9 17h4"></path>
                        </svg>

                    </div>

                    <div class="file-selected-info">

                        <p class="file-selected-name">
                            {{ $submission->original_name }}
                        </p>

                        <p class="file-selected-size">
                            Entregado:
                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                        </p>

                    </div>

                </div>

                <div class="btn-group mt-2">

                    <a
                        href="{{ route('student.submissions.view', $submission) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-secondary"
                    >
                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>

                        Ver PDF
                    </a>

                    <a
                        href="{{ route('student.submissions.download', $submission) }}"
                        class="btn btn-ghost"
                    >
                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12 3v12"></path>
                            <path d="m7 10 5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>

                        Descargar
                    </a>

                </div>

            </div>

        @else

            <div class="card-body">

                <x-empty-state
                    title="Sin evidencia"
                    description="Todavía no has enviado un archivo para esta tarea."
                />

            </div>

        @endif

    </section>

    <section class="card">

        <div class="card-header">

            <div class="card-header-content">

                <h2 class="card-title">
                    Calificación
                </h2>

                <p class="card-description">
                    Resultado publicado por el docente.
                </p>

            </div>

        </div>

        <div class="card-body">

            @if($publishedGrade)

                <div class="grade-display">

                    <p class="grade-display-value">
                        {{ number_format(
                            (float) $publishedGrade->score,
                            1
                        ) }}
                    </p>

                    <p class="grade-display-total">
                        de 10
                    </p>

                </div>

                <x-status-badge type="success">
                    Calificación publicada
                </x-status-badge>

            @elseif($submission)

                <div class="empty-state">

                    <div class="empty-state-icon">

                        <svg
                            class="icon icon-lg"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>

                    </div>

                    <h3 class="empty-state-title">
                        Pendiente de evaluación
                    </h3>

                    <p class="empty-state-description">
                        El docente todavía no ha publicado
                        una calificación.
                    </p>

                </div>

            @else

                <x-empty-state
                    title="Sin calificación"
                    description="Esta tarea todavía no tiene una evidencia evaluable."
                />

            @endif

        </div>

    </section>

</div>

@if($task->canSubmit())

    <section class="card mt-3">

        <div class="card-header">

            <div class="card-header-content">

                <h2 class="card-title">
                    {{ $submission
                        ? 'Reemplazar evidencia'
                        : 'Enviar evidencia' }}
                </h2>

                <p class="card-description">

                    @if($submission)
                        Puedes sustituir tu archivo mientras
                        la tarea continúe vigente.
                    @else
                        Selecciona el archivo PDF que deseas entregar.
                    @endif

                </p>

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('student.tasks.submit', $task) }}"
            enctype="multipart/form-data"
            data-prevent-double-submit
        >

            @csrf

            <div class="card-body">

                @if($submission)

                    <x-alert
                        type="warning"
                        title="Reemplazo de evidencia"
                        persistent
                    >
                        Al enviar un nuevo archivo, la evidencia
                        anterior será sustituida. Solo se conserva
                        un archivo por tarea.
                    </x-alert>

                @endif

                <div
                    class="file-upload"
                    data-file-upload
                    data-file-preview="evidence-preview"
                >

                    <input
                        id="evidence"
                        type="file"
                        name="evidence"
                        accept=".pdf,application/pdf"
                        data-file-input
                        data-file-type="pdf"
                        data-max-size="2097152"
                        required
                    >

                    <div class="file-upload-icon">

                        <svg
                            class="icon icon-lg"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12 16V4"></path>
                            <path d="m7 9 5-5 5 5"></path>
                            <path d="M5 20h14"></path>
                        </svg>

                    </div>

                    <p class="file-upload-title">
                        Selecciona o arrastra tu PDF
                    </p>

                    <p class="file-upload-description">
                        Solo PDF · máximo 2 MB
                    </p>

                </div>

                <div
                    id="evidence-preview"
                    class="file-selected hidden"
                    data-file-preview
                >

                    <div class="file-selected-icon">

                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                        >
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                            <path d="M14 2v6h6"></path>
                        </svg>

                    </div>

                    <div class="file-selected-info">

                        <p
                            class="file-selected-name"
                            data-file-name
                        ></p>

                        <p
                            class="file-selected-size"
                            data-file-size
                        ></p>

                    </div>

                </div>

                @error('evidence')
                    <div class="form-error mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="card-footer">

                <button
                    type="submit"
                    class="btn btn-primary"
                    @if($submission)
                        data-confirm="¿Deseas reemplazar la evidencia actual por este archivo?"
                    @else
                        data-confirm="¿Confirmas que deseas enviar esta evidencia?"
                    @endif
                >

                    @if($submission)

                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M20 7h-9"></path>
                            <path d="m16 3 4 4-4 4"></path>
                            <path d="M4 17h9"></path>
                            <path d="m8 13-4 4 4 4"></path>
                        </svg>

                        Reemplazar evidencia

                    @else

                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12 16V4"></path>
                            <path d="m7 9 5-5 5 5"></path>
                            <path d="M5 20h14"></path>
                        </svg>

                        Enviar evidencia

                    @endif

                </button>

            </div>

        </form>

    </section>

@elseif($submission)

    <x-alert
        type="info"
        title="Entrega cerrada"
        persistent
        class="mt-3"
    >
        Tu evidencia permanece disponible para consulta,
        pero ya no puede ser reemplazada porque el periodo
        de entrega terminó.
    </x-alert>

@endif

<div class="mt-3">

    <a
        href="{{ route('student.tasks.index') }}"
        class="btn btn-secondary"
    >
        Volver a mis tareas
    </a>

</div>

@endsection