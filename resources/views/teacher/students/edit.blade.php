@extends('layouts.app')

@section('title', 'Editar alumno')
@section('page-title', 'Editar alumno')
@section('page-subtitle', $student->name)

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.students.index') }}">
        Alumnos
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>{{ $student->name }}</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Editar alumno
        </h1>

        <p class="page-description">
            Actualiza los datos de acceso y perfil del alumno.
        </p>
    </div>

    <div class="page-actions">
        @if($student->is_active)
            <x-status-badge type="success">
                Cuenta activa
            </x-status-badge>
        @else
            <x-status-badge type="danger">
                Cuenta inactiva
            </x-status-badge>
        @endif
    </div>
</div>

<div class="card">
    <form
        method="POST"
        action="{{ route('teacher.students.update', $student) }}"
        data-prevent-double-submit
    >
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Información del alumno
                </h2>

                <p class="card-description">
                    Matrícula:
                    {{ $student->enrollment_number }}
                </p>
            </div>
        </div>

        <div class="card-body">
            <div class="form-grid">

                <div class="form-group form-span-full">
                    <label
                        for="name"
                        class="form-label form-label-required"
                    >
                        Nombre completo
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $student->name) }}"
                        maxlength="120"
                        required
                    >

                    @error('name')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="enrollment_number"
                        class="form-label form-label-required"
                    >
                        Matrícula
                    </label>

                    <input
                        id="enrollment_number"
                        type="text"
                        name="enrollment_number"
                        class="form-control @error('enrollment_number') is-invalid @enderror"
                        value="{{ old('enrollment_number', $student->enrollment_number) }}"
                        maxlength="30"
                        required
                    >

                    @error('enrollment_number')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="email"
                        class="form-label form-label-required"
                    >
                        Correo electrónico
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $student->email) }}"
                        maxlength="150"
                        required
                    >

                    @error('email')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="password"
                        class="form-label"
                    >
                        Nueva contraseña
                    </label>

                    <div class="password-wrapper">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="password"
                        >
                            <svg
                                class="icon icon-sm"
                                data-password-icon="show"
                                viewBox="0 0 24 24"
                            >
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>

                            <svg
                                class="icon icon-sm hidden"
                                data-password-icon="hide"
                                viewBox="0 0 24 24"
                            >
                                <path d="m3 3 18 18"></path>
                            </svg>
                        </button>
                    </div>

                    <span class="form-help">
                        Déjalo vacío para conservar la contraseña actual.
                    </span>

                    @error('password')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        Confirmar nueva contraseña
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        minlength="8"
                        autocomplete="new-password"
                    >
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar cambios
                </button>

                <a
                    href="{{ route('teacher.students.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

@endsection