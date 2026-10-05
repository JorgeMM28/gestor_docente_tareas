@extends('layouts.app')

@section('title', 'Mis tareas')
@section('page-title', 'Mis tareas')
@section('page-subtitle', 'Actividades asignadas')

@section('content')

<div class="page-header">

    <div class="page-header-content">
        <h1 class="page-title">
            Mis tareas
        </h1>

        <p class="page-description">
            Consulta las actividades asignadas y el estado de tus entregas.
        </p>
    </div>

</div>

<form
    method="GET"
    action="{{ route('student.tasks.index') }}"
    class="filters"
>

    <div class="filter-search">

        <label
            for="search"
            class="form-label"
        >
            Buscar
        </label>

        <div class="search-control">

            <svg class="icon" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>

            <input
                id="search"
                type="search"
                name="search"
                class="form-control"
                value="{{ $search }}"
                placeholder="Buscar tarea"
            >

        </div>

    </div>

    <div class="filter-item">

        <label
            for="status"
            class="form-label"
        >
            Estado
        </label>

        <select
            id="status"
            name="status"
            class="form-select"
            data-auto-submit
        >

            <option
                value="all"
                @selected($status === 'all')
            >
                Todas
            </option>

            <option
                value="pending"
                @selected($status === 'pending')
            >
                Pendientes
            </option>

            <option
                value="submitted"
                @selected($status === 'submitted')
            >
                Entregadas
            </option>

            <option
                value="expired"
                @selected($status === 'expired')
            >
                Finalizadas
            </option>

            <option
                value="cancelled"
                @selected($status === 'cancelled')
            >
                Canceladas
            </option>

        </select>

    </div>

    <div class="filter-item">

        <label
            for="type"
            class="form-label"
        >
            Tipo
        </label>

        <select
            id="type"
            name="type"
            class="form-select"
            data-auto-submit
        >

            <option
                value="all"
                @selected($type === 'all')
            >
                Todos
            </option>

            <option
                value="homework"
                @selected($type === 'homework')
            >
                Tarea en casa
            </option>

            <option
                value="class_activity"
                @selected($type === 'class_activity')
            >
                Actividad en clase
            </option>

            <option
                value="presentation"
                @selected($type === 'presentation')
            >
                Exposición
            </option>

        </select>

    </div>

    <button
        type="submit"
        class="btn btn-secondary"
    >
        Buscar
    </button>

    @if(
        $search !== ''
        || $status !== 'all'
        || $type !== 'all'
    )

        <a
            href="{{ route('student.tasks.index') }}"
            class="btn btn-ghost"
        >
            Limpiar
        </a>

    @endif

</form>

<div class="table-container">

    @if($tasks->isEmpty())

        <x-empty-state
            title="No se encontraron tareas"
            description="No existen tareas que coincidan con los filtros seleccionados."
        />

    @else

        <div class="table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Tarea</th>
                        <th>Tipo</th>
                        <th>Fecha límite</th>
                        <th>Entrega</th>
                        <th>Calificación</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($tasks as $task)

                        @php
                            $submission = $task->submissions->first();
                            $publishedGrade =
                                $submission?->grade?->isPublished()
                                    ? $submission->grade
                                    : null;
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

                                @if($task->isCancelled())

                                    <x-status-badge type="neutral">
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
                                    —
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

        <div class="table-footer">

            <p class="table-count">
                {{ $tasks->total() }}
                tarea{{ $tasks->total() === 1 ? '' : 's' }}
            </p>

            @if($tasks->hasPages())
                {{ $tasks->links() }}
            @endif

        </div>

    @endif

</div>

@endsection