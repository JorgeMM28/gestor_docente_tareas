@extends('layouts.app')

@section('title', 'Nuevo alumno')
@section('page-title', 'Nuevo alumno')
@section('page-subtitle', 'Registro individual')

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.students.index') }}">
        Alumnos
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>Nuevo alumno</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Registrar alumno
        </h1>

        <p class="page-description">
            Crea una cuenta para un nuevo alumno del grupo.
        </p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-content">
            <h2 class="card-title">
                Información del alumno
            </h2>

            <p class="card-description">
                Todos los campos son obligatorios.
            </p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('teacher.students.store') }}"
        data-prevent-double-submit
    >
        @csrf

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
                        value="{{ old('name') }}"
                        maxlength="120"
                        autocomplete="name"
                        data-trim
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
                        value="{{ old('enrollment_number') }}"
                        maxlength="30"
                        autocomplete="off"
                        data-trim
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
                        value="{{ old('email') }}"
                        maxlength="150"
                        autocomplete="email"
                        data-trim
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
                        class="form-label form-label-required"
                    >
                        Contraseña
                    </label>

                    <div class="password-wrapper">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="password"
                            aria-label="Mostrar contraseña"
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
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                                <path d="M9.9 4.2A10.5 10.5 0 0 1 12 4c6.5 0 10 8 10 8a17 17 0 0 1-2 3"></path>
                                <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.2-.9"></path>
                            </svg>
                        </button>
                    </div>

                    <span class="form-help">
                        Mínimo 8 caracteres.
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
                        class="form-label form-label-required"
                    >
                        Confirmar contraseña
                    </label>

                    <div class="password-wrapper">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="password_confirmation"
                            aria-label="Mostrar contraseña"
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
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar alumno
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