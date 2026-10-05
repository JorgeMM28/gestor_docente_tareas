@extends('layouts.app')

@section('title', 'Nueva tarea')
@section('page-title', 'Nueva tarea')
@section('page-subtitle', 'Asignar actividad al grupo')

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.tasks.index') }}">
        Tareas
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>Nueva tarea</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Crear tarea
        </h1>

        <p class="page-description">
            La tarea será asignada a todos los alumnos activos.
            Una vez creada no podrá editarse.
        </p>
    </div>
</div>

<x-alert
    type="info"
    title="Tarea inmutable"
    persistent
>
    Verifica las instrucciones y la fecha antes de publicar.
    Después de crear la tarea solamente podrás cancelarla.
</x-alert>

<div class="card">
    <form
        method="POST"
        action="{{ route('teacher.tasks.store') }}"
        data-prevent-double-submit
    >
        @csrf

        <div class="card-body">
            <div class="form-grid">

                <div class="form-group form-span-full">
                    <label
                        for="title"
                        class="form-label form-label-required"
                    >
                        Título
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}"
                        maxlength="150"
                        required
                    >

                    @error('title')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="type"
                        class="form-label form-label-required"
                    >
                        Tipo
                    </label>

                    <select
                        id="type"
                        name="type"
                        class="form-select @error('type') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Selecciona un tipo
                        </option>

                        <option
                            value="homework"
                            @selected(old('type') === 'homework')
                        >
                            Tarea en casa
                        </option>

                        <option
                            value="class_activity"
                            @selected(old('type') === 'class_activity')
                        >
                            Actividad en clase
                        </option>

                        <option
                            value="presentation"
                            @selected(old('type') === 'presentation')
                        >
                            Exposición
                        </option>
                    </select>

                    @error('type')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="due_date"
                        class="form-label form-label-required"
                    >
                        Fecha límite
                    </label>

                    <input
                        id="due_date"
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date') }}"
                        data-min-today
                        required
                    >

                    <span class="form-help">
                        La entrega estará disponible hasta las 23:59:59 de ese día.
                    </span>

                    @error('due_date')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group form-span-full">
                    <label
                        for="description"
                        class="form-label form-label-required"
                    >
                        Instrucciones
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-textarea @error('description') is-invalid @enderror"
                        maxlength="5000"
                        rows="10"
                        required
                    >{{ old('description') }}</textarea>

                    <span class="form-help">
                        Describe claramente lo que debe entregar el alumno.
                    </span>

                    @error('description')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    data-confirm="¿Confirmas la publicación de esta tarea? Después no podrá editarse."
                >
                    Publicar tarea
                </button>

                <a
                    href="{{ route('teacher.tasks.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

@endsection