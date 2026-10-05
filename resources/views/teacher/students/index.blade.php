@extends('layouts.app')

@section('title', 'Alumnos')
@section('page-title', 'Alumnos')
@section('page-subtitle', 'Administración de alumnos')

@section('content')

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Alumnos
        </h1>

        <p class="page-description">
            Administra las cuentas de los alumnos del grupo.
        </p>
    </div>

    <div class="page-actions">
        <a
            href="{{ route('teacher.students.import.form') }}"
            class="btn btn-secondary"
        >
            <svg class="icon icon-sm" viewBox="0 0 24 24">
                <path d="M12 3v12"></path>
                <path d="m7 10 5 5 5-5"></path>
                <path d="M5 21h14"></path>
            </svg>

            Importar CSV
        </a>

        <a
            href="{{ route('teacher.students.create') }}"
            class="btn btn-primary"
        >
            <svg class="icon icon-sm" viewBox="0 0 24 24">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
            </svg>

            Nuevo alumno
        </a>
    </div>
</div>

<form
    method="GET"
    action="{{ route('teacher.students.index') }}"
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
                placeholder="Nombre, correo o matrícula"
                value="{{ $search }}"
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
                Todos
            </option>

            <option
                value="active"
                @selected($status === 'active')
            >
                Activos
            </option>

            <option
                value="inactive"
                @selected($status === 'inactive')
            >
                Inactivos
            </option>
        </select>
    </div>

    <button
        type="submit"
        class="btn btn-secondary"
    >
        Buscar
    </button>

    @if($search !== '' || $status !== 'all')
        <a
            href="{{ route('teacher.students.index') }}"
            class="btn btn-ghost"
        >
            Limpiar
        </a>
    @endif
</form>

<div class="table-container">

    @if($students->isEmpty())
        <x-empty-state
            title="No se encontraron alumnos"
            description="No existen alumnos que coincidan con los filtros seleccionados."
        />
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Matrícula</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>
                                <p class="table-primary-text">
                                    {{ $student->name }}
                                </p>
                            </td>

                            <td class="table-mono">
                                {{ $student->enrollment_number }}
                            </td>

                            <td>
                                {{ $student->email }}
                            </td>

                            <td>
                                @if($student->is_active)
                                    <x-status-badge type="success">
                                        Activo
                                    </x-status-badge>
                                @else
                                    <x-status-badge type="danger">
                                        Inactivo
                                    </x-status-badge>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="{{ route('teacher.students.edit', $student) }}"
                                        class="btn btn-secondary btn-sm"
                                        data-tooltip="Editar alumno"
                                    >
                                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                        </svg>
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('teacher.students.toggle-status', $student) }}"
                                        data-prevent-double-submit
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn {{ $student->is_active ? 'btn-danger' : 'btn-success' }} btn-sm"
                                            data-confirm="{{ $student->is_active
                                                ? '¿Deseas desactivar a este alumno? Ya no podrá iniciar sesión.'
                                                : '¿Deseas activar nuevamente a este alumno?' }}"
                                        >
                                            @if($student->is_active)
                                                Desactivar
                                            @else
                                                Activar
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p class="table-count">
                Mostrando
                {{ $students->firstItem() }}
                -
                {{ $students->lastItem() }}
                de
                {{ $students->total() }}
                alumnos
            </p>

            @if($students->hasPages())
                {{ $students->links() }}
            @endif
        </div>
    @endif
</div>

@endsection