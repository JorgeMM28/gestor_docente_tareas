<!DOCTYPE html>
<html
    lang="es"
    data-theme="light"
>
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Iniciar sesión | Sistema Docente</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet"
    >

    <script>
        (() => {
            const savedTheme =
                localStorage.getItem('sistema-docente-theme');

            if (
                savedTheme === 'light'
                || savedTheme === 'dark'
            ) {
                document.documentElement.dataset.theme =
                    savedTheme;

                return;
            }

            document.documentElement.dataset.theme =
                window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches
                    ? 'dark'
                    : 'light';
        })();
    </script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="login-page">

    <div class="login-shell">

        <section class="login-brand-panel">

            <div class="login-brand-content">

                <div class="login-brand">

                    <div class="login-brand-mark">
                        <svg
                            class="icon icon-lg"
                            viewBox="0 0 24 24"
                        >
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h6"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="login-brand-name">
                            Sistema Docente
                        </p>

                        <p class="login-brand-subtitle">
                            Gestión de tareas y evidencias
                        </p>
                    </div>

                </div>

                <div class="login-hero">

                    <p class="login-eyebrow">
                        PLATAFORMA ACADÉMICA
                    </p>

                    <h1 class="login-hero-title">
                        Organiza tareas,
                        evidencias y
                        evaluaciones.
                    </h1>

                    <p class="login-hero-description">
                        Un espacio centralizado para administrar
                        actividades, entregas y calificaciones del grupo.
                    </p>

                </div>

                <div class="login-feature-list">

                    <div class="login-feature">

                        <div class="login-feature-icon">
                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                        </div>

                        <div>
                            <p class="login-feature-title">
                                Seguimiento de entregas
                            </p>

                            <p class="login-feature-text">
                                Consulta rápidamente quién entregó
                                y quién continúa pendiente.
                            </p>
                        </div>

                    </div>

                    <div class="login-feature">

                        <div class="login-feature-icon">
                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                <path d="M14 2v6h6"></path>
                            </svg>
                        </div>

                        <div>
                            <p class="login-feature-title">
                                Evidencias en PDF
                            </p>

                            <p class="login-feature-text">
                                Cada actividad mantiene una evidencia
                                centralizada y disponible para consulta.
                            </p>
                        </div>

                    </div>

                    <div class="login-feature">

                        <div class="login-feature-icon">
                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12 2 15 8l6 .9-4.5 4.4 1.1 6.2L12 16.6l-5.6 2.9 1.1-6.2L3 8.9 9 8Z"></path>
                            </svg>
                        </div>

                        <div>
                            <p class="login-feature-title">
                                Evaluación controlada
                            </p>

                            <p class="login-feature-text">
                                Guarda calificaciones como borrador
                                y publícalas cuando estén listas.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

            <div class="login-brand-footer">
                Sistema de gestión académica
            </div>

        </section>

        <main class="login-form-panel">

            <div class="login-top-actions">

                <button
                    type="button"
                    class="theme-toggle"
                    data-theme-toggle
                    aria-label="Cambiar tema"
                >

                    <svg
                        class="icon"
                        data-theme-icon="dark"
                        viewBox="0 0 24 24"
                    >
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2"></path>
                        <path d="M12 20v2"></path>
                        <path d="m4.93 4.93 1.41 1.41"></path>
                        <path d="m17.66 17.66 1.41 1.41"></path>
                        <path d="M2 12h2"></path>
                        <path d="M20 12h2"></path>
                        <path d="m6.34 17.66-1.41 1.41"></path>
                        <path d="m19.07 4.93-1.41 1.41"></path>
                    </svg>

                    <svg
                        class="icon hidden"
                        data-theme-icon="light"
                        viewBox="0 0 24 24"
                    >
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>
                    </svg>

                </button>

            </div>

            <div class="login-form-container">

                <div class="login-mobile-brand">

                    <div class="login-brand-mark">
                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                        >
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path>
                        </svg>
                    </div>

                    <span>
                        Sistema Docente
                    </span>

                </div>

                <div class="login-heading">

                    <p class="login-eyebrow">
                        BIENVENIDO
                    </p>

                    <h2 class="login-title">
                        Iniciar sesión
                    </h2>

                    <p class="login-description">
                        Ingresa tus credenciales para acceder
                        a la plataforma.
                    </p>

                </div>

                @if(session('success'))

                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>

                @endif

                @if(session('error'))

                    <x-alert type="danger">
                        {{ session('error') }}
                    </x-alert>

                @endif

                @if($errors->has('login'))

                    <x-alert
                        type="danger"
                        persistent
                    >
                        {{ $errors->first('login') }}
                    </x-alert>

                @endif

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="login-form"
                    data-prevent-double-submit
                >

                    @csrf

                    <div class="form-group">

                        <label
                            for="email"
                            class="form-label form-label-required"
                        >
                            Correo electrónico
                        </label>

                        <div class="input-icon-wrapper">

                            <div class="input-icon">
                                <svg
                                    class="icon icon-sm"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    ></rect>

                                    <path d="m3 7 9 6 9-6"></path>
                                </svg>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="correo@ejemplo.com"
                                autocomplete="email"
                                autofocus
                                required
                            >

                        </div>

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

                            <div class="input-icon">
                                <svg
                                    class="icon icon-sm"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="4"
                                        y="10"
                                        width="16"
                                        height="11"
                                        rx="2"
                                    ></rect>

                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                </svg>
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
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

                        @error('password')
                            <div class="form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <label class="checkbox-control">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            @checked(old('remember'))
                        >

                        <span>
                            Mantener sesión iniciada
                        </span>

                    </label>

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg login-submit"
                    >
                        Iniciar sesión

                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>
                    </button>

                </form>

                <div class="login-help">

                    <svg
                        class="icon icon-sm"
                        viewBox="0 0 24 24"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 16h.01"></path>
                        <path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"></path>
                    </svg>

                    <p>
                        Si tienes problemas para acceder,
                        comunícate con el docente.
                    </p>

                </div>

            </div>

        </main>

    </div>

</body>
</html>