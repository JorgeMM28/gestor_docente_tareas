@extends('layouts.app')

@section('title', 'Mis entregas')
@section('page-title', 'Mis entregas')
@section('page-subtitle', 'Historial de evidencias y calificaciones')

@section('content')

<div class="page-header">

    <div class="page-header-content">

        <h1 class="page-title">
            Mis entregas
        </h1>

        <p class="page-description">
            Consulta las evidencias que has enviado y
            las calificaciones publicadas.
        </p>

    </div>

</div>

<div class="table-container">

    @if($submissions->isEmpty())

        <x-empty-state
            title="Sin entregas"
            description="Todavía no has enviado evidencias."
        >
            <x-slot:action>

                <a
                    href="{{ route('student.tasks.index') }}"
                    class="btn btn-primary"
                >
                    Ver tareas
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
                        <th>Fecha de entrega</th>
                        <th>Evidencia</th>
                        <th>Calificación</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($submissions as $submission)

                        @php
                            $publishedGrade =
                                $submission->grade?->isPublished()
                                    ? $submission->grade
                                    : null;
                        @endphp

                        <tr>

                            <td>

                                <p class="table-primary-text">
                                    {{ $submission->task->title }}
                                </p>

                                @if($submission->task->isCancelled())

                                    <p class="table-secondary-text">
                                        Tarea cancelada
                                    </p>

                                @else

                                    <p class="table-secondary-text">
                                        Límite:
                                        {{ $submission->task->due_at->format('d/m/Y') }}
                                    </p>

                                @endif

                            </td>

                            <td>

                                <x-status-badge type="type">
                                    {{ $submission->task->type_label }}
                                </x-status-badge>

                            </td>

                            <td>

                                <p class="table-primary-text table-mono">
                                    {{ $submission->submitted_at->format('d/m/Y') }}
                                </p>

                                <p class="table-secondary-text table-mono">
                                    {{ $submission->submitted_at->format('H:i') }}
                                </p>

                            </td>

                            <td>

                                <a
                                    href="{{ route('student.submissions.view', $submission) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Ver PDF
                                </a>

                            </td>

                            <td>

                                @if($publishedGrade)

                                    <span class="grade-value">

                                        <span class="grade-value-number">
                                            {{ number_format(
                                                (float) $publishedGrade->score,
                                                1
                                            ) }}
                                        </span>

                                        <span class="grade-value-total">
                                            /10
                                        </span>

                                    </span>

                                @else

                                    <x-status-badge type="neutral">
                                        Pendiente
                                    </x-status-badge>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route('student.tasks.show', $submission->task) }}"
                                    class="btn btn-ghost btn-sm"
                                >
                                    Detalle
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="table-footer">

            <p class="table-count">
                {{ $submissions->total() }}
                entrega{{ $submissions->total() === 1 ? '' : 's' }}
            </p>

            @if($submissions->hasPages())
                {{ $submissions->links() }}
            @endif

        </div>

    @endif

</div>

@endsection