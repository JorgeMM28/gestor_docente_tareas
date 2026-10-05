@extends('layouts.app')

@section('title', 'Importar alumnos')
@section('page-title', 'Importar alumnos')
@section('page-subtitle', 'Carga masiva mediante CSV')

@section('content')

<div class="breadcrumbs">
    <a href="{{ route('teacher.students.index') }}">
        Alumnos
    </a>

    <span class="breadcrumb-separator">/</span>

    <span>Importar CSV</span>
</div>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Importar alumnos
        </h1>

        <p class="page-description">
            Registra varios alumnos utilizando un archivo CSV.
        </p>
    </div>
</div>

<div class="dashboard-grid">

    <section class="card">
        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Archivo CSV
                </h2>

                <p class="card-description">
                    Tamaño máximo: 2 MB.
                </p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('teacher.students.import') }}"
            enctype="multipart/form-data"
            data-prevent-double-submit
        >
            @csrf

            <div class="card-body">
                <div
                    class="file-upload"
                    data-file-upload
                    data-file-preview="csv-preview"
                >
                    <input
                        type="file"
                        name="csv_file"
                        accept=".csv,text/csv"
                        data-file-input
                        data-file-type="csv"
                        data-max-size="2097152"
                        required
                    >

                    <div class="file-upload-icon">
                        <svg class="icon icon-lg" viewBox="0 0 24 24">
                            <path d="M12 3v12"></path>
                            <path d="m7 10 5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>
                    </div>

                    <p class="file-upload-title">
                        Selecciona o arrastra el archivo CSV
                    </p>

                    <p class="file-upload-description">
                        Solo se admiten archivos .csv
                    </p>
                </div>

                <div
                    id="csv-preview"
                    class="file-selected hidden"
                    data-file-preview
                >
                    <div class="file-selected-icon">
                        <svg class="icon" viewBox="0 0 24 24">
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

                @error('csv_file')
                    <div class="form-error mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="card-footer">
                <div class="btn-group">
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Importar alumnos
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
    </section>

    <aside class="card">
        <div class="card-header">
            <div class="card-header-content">
                <h2 class="card-title">
                    Formato requerido
                </h2>

                <p class="card-description">
                    El encabezado debe respetarse exactamente.
                </p>
            </div>
        </div>

        <div class="card-body">
            <p class="text-secondary">
                El archivo debe contener cuatro columnas:
            </p>

            <div class="code-block">name,email,enrollment_number,password
Juan Pérez,juan@sistema.test,20260016,Alumno123!
María López,maria@sistema.test,20260017,Alumno123!</div>

            <div class="divider"></div>

            <p class="text-secondary mb-0">
                Si existe un correo o matrícula duplicada,
                la importación completa será cancelada para
                evitar registros parciales.
            </p>
        </div>
    </aside>

</div>

@endsection