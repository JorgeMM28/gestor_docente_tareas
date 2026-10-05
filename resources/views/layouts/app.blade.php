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

    <title>
        @yield('title', 'Sistema Docente')
        | Sistema Docente
    </title>

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

<body>

    @php
        $user = auth()->user();
        $isTeacher = $user && $user->role === 'teacher';
        $isStudent = $user && $user->role === 'student';
    @endphp

    <div class="app-shell">

        <aside class="sidebar">

            <div class="sidebar-header">

                <a
                    href="{{ $isTeacher
                        ? route('teacher.dashboard')
                        : route('student.dashboard') }}"
                    class="sidebar-brand"
                >

                    <div class="sidebar-brand-mark">

                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                        >
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h6"></path>
                        </svg>

                    </div>

                    <div class="sidebar-brand-text">

                        <span class="sidebar-brand-name">
                            Sistema Docente
                        </span>

                        <span class="sidebar-brand-role">
                            {{ $isTeacher
                                ? 'Panel del docente'
                                : 'Portal del alumno' }}
                        </span>

                    </div>

                </a>

                <button
                    type="button"
                    class="sidebar-mobile-close"
                    data-sidebar-close
                    aria-label="Cerrar menú"
                >
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>

            </div>

            <div class="sidebar-content">

                <nav class="sidebar-nav">

                    @if($isTeacher)

                        <p class="sidebar-section-label">
                            PRINCIPAL
                        </p>

                        <a
                            href="{{ route('teacher.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <rect
                                    x="3"
                                    y="3"
                                    width="7"
                                    height="7"
                                    rx="1"
                                ></rect>

                                <rect
                                    x="14"
                                    y="3"
                                    width="7"
                                    height="7"
                                    rx="1"
                                ></rect>

                                <rect
                                    x="3"
                                    y="14"
                                    width="7"
                                    height="7"
                                    rx="1"
                                ></rect>

                                <rect
                                    x="14"
                                    y="14"
                                    width="7"
                                    height="7"
                                    rx="1"
                                ></rect>
                            </svg>

                            <span>
                                Dashboard
                            </span>

                        </a>

                        <p class="sidebar-section-label">
                            GESTIÓN
                        </p>

                        <a
                            href="{{ route('teacher.students.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>

                            <span>
                                Alumnos
                            </span>

                        </a>

                        <a
                            href="{{ route('teacher.tasks.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.tasks.*') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                <path d="M14 2v6h6"></path>
                                <path d="M9 13h6"></path>
                                <path d="M9 17h6"></path>
                            </svg>

                            <span>
                                Tareas
                            </span>

                        </a>

                    @elseif($isStudent)

                        <p class="sidebar-section-label">
                            PRINCIPAL
                        </p>

                        <a
                            href="{{ route('student.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                            </svg>

                            <span>
                                Inicio
                            </span>

                        </a>

                        <p class="sidebar-section-label">
                            ACTIVIDADES
                        </p>

                        <a
                            href="{{ route('student.tasks.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.tasks.*') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                                <path d="M14 2v6h6"></path>
                                <path d="M9 13h6"></path>
                                <path d="M9 17h6"></path>
                            </svg>

                            <span>
                                Mis tareas
                            </span>

                        </a>

                        <a
                            href="{{ route('student.submissions.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.submissions.*') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M21.44 11.05 12.25 20.24a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                            </svg>

                            <span>
                                Mis entregas
                            </span>

                        </a>

                        <a
                            href="{{ route('student.notifications.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.notifications.*') ? 'active' : '' }}"
                        >

                            <svg
                                class="sidebar-link-icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>

                            <span>
                                Notificaciones
                            </span>

                            @if(
                                isset($unreadNotificationsCount)
                                && $unreadNotificationsCount > 0
                            )
                                <span class="sidebar-count">
                                    {{ $unreadNotificationsCount > 99
                                        ? '99+'
                                        : $unreadNotificationsCount }}
                                </span>
                            @endif

                        </a>

                    @endif

                </nav>

            </div>

            <div class="sidebar-footer">

                <div class="sidebar-user">

                    <div class="sidebar-user-avatar">
                        {{ mb_strtoupper(
                            mb_substr($user->name, 0, 1)
                        ) }}
                    </div>

                    <div class="sidebar-user-info">

                        <p class="sidebar-user-name">
                            {{ $user->name }}
                        </p>

                        <p class="sidebar-user-role">
                            {{ $isTeacher
                                ? 'Docente'
                                : $user->enrollment_number }}
                        </p>

                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="sidebar-logout"
                    >

                        <svg
                            class="icon icon-sm"
                            viewBox="0 0 24 24"
                        >
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="m16 17 5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>

                        <span>
                            Cerrar sesión
                        </span>

                    </button>
                </form>

            </div>

        </aside>

        <div
            class="sidebar-overlay"
            data-sidebar-close
        ></div>

        <div class="app-main">

            <header class="topbar">

                <div class="topbar-left">

                    <button
                        type="button"
                        class="topbar-menu-button"
                        data-sidebar-open
                        aria-label="Abrir menú"
                    >
                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                        >
                            <path d="M4 6h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="topbar-heading">

                        <p class="topbar-title">
                            @yield('page-title', 'Sistema Docente')
                        </p>

                        <p class="topbar-subtitle">
                            @yield('page-subtitle')
                        </p>

                    </div>

                </div>

                <div class="topbar-actions">

                    @if($isStudent)

                        <a
                            href="{{ route('student.notifications.index') }}"
                            class="topbar-action topbar-notification-button"
                            aria-label="Notificaciones"
                        >

                            <svg
                                class="icon"
                                viewBox="0 0 24 24"
                            >
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>

                            @if(
                                isset($unreadNotificationsCount)
                                && $unreadNotificationsCount > 0
                            )
                                <span class="topbar-notification-dot"></span>
                            @endif

                        </a>

                    @endif

                    <button
                        type="button"
                        class="topbar-action"
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

                    <div class="topbar-user">

                        <div class="topbar-avatar">
                            {{ mb_strtoupper(
                                mb_substr($user->name, 0, 1)
                            ) }}
                        </div>

                        <div class="topbar-user-text">

                            <span class="topbar-user-name">
                                {{ $user->name }}
                            </span>

                            <span class="topbar-user-role">
                                {{ $isTeacher
                                    ? 'Docente'
                                    : 'Alumno' }}
                            </span>

                        </div>

                    </div>

                </div>

            </header>

            <main class="content">

                @if(session('success'))

                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>

                @endif

                @if(session('error'))

                    <x-alert
                        type="danger"
                        persistent
                    >
                        {{ session('error') }}
                    </x-alert>

                @endif

                @if(session('warning'))

                    <x-alert
                        type="warning"
                        persistent
                    >
                        {{ session('warning') }}
                    </x-alert>

                @endif

                @if(session('info'))

                    <x-alert type="info">
                        {{ session('info') }}
                    </x-alert>

                @endif

                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>