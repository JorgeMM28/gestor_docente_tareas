@extends('layouts.app')

@section('title', 'Tareas')
@section('page-title', 'Tareas')
@section('page-subtitle', 'Gestión de tareas y actividades')

@section('content')

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Tareas
        </h1>

        <p class="page-description">
            Consulta las tareas asignadas, entregas y estado de evaluación.
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

<form
    method="GET"
    action="{{ route('teacher.tasks.index') }}"
    class="filters"
>
    <div class="filter-search">
        <label class="form-label" for="search">
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
        <label class="form-label" for="status">
            Estado
        </label>

        <select
            id="status"
            name="status"
            class="form-select"
            data-auto-submit
        >
            <option value="all" @selected($status === 'all')}>
                Todas
            </option>

            <option value="active" @selected($status === 'active')}>
                Vigentes
            </option>

            <option value="expired" @selected($status === 'expired')}>
                Finalizadas
            </option>

            <option value="cancelled" @selected($status === 'cancelled')}>
                Canceladas
            </option>
        </select>
    </div>

    <div class="filter-item">
        <label class="form-label" for="type">
            Tipo
        </label>

        <select
            id="type"
            name="type"
            class="form-select"
            data-auto-submit
        >
            <option value="all" @selected($type === 'all')}>
                Todos
            </option>

            <option value="homework" @selected($type === 'homework')}>
                Tarea en casa
            </option>

            <option value="class_activity" @selected($type === 'class_activity')}>
                Actividad en clase
            </option>

            <option value="presentation" @selected($type === 'presentation')}>
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
</form>

<div class="table-container">

    @if($tasks->isEmpty())
        <x-empty-state
            title="No hay tareas"
            description="No existen tareas que coincidan con los filtros seleccionados."
        >
            <x-slot:action>
                <a
                    href="{{ route('teacher.tasks.create') }}"
                    class="btn btn-primary"
                >
                    Crear tarea
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
                        <th>Fecha límite</th>
                        <th>Entregas</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <p class="table-primary-text">
                                    {{ $task->title }}
                                </p>

                                <p class="table-secondary-text">
                                    Asignada
                                    {{ $task->assigned_at->format('d/m/Y H:i') }}
                                </p>
                            </td>

                            <td>
                                <x-status-badge type="type">
                                    {{ $task->type_label }}
                                </x-status-badge>
                            </td>

                            <td class="table-mono">
                                {{ $task->due_at->format('d/m/Y 23:59') }}
                            </td>

                            <td>
                                {{ $task->submissions_count }}
                                /
                                {{ $activeStudentsCount }}
                            </td>

                            <td>
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
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="{{ route('teacher.tasks.show', $task) }}"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Ver detalle
                                    </a>
                                </div>
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