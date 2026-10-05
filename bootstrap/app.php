<?php

use App\Http\Middleware\EnsureStudent;
use App\Http\Middleware\EnsureTeacher;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\View;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Alias de middleware
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'teacher' => EnsureTeacher::class,
            'student' => EnsureStudent::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->booted(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Datos globales de las vistas
        |--------------------------------------------------------------------------
        |
        | Compartimos el número de notificaciones sin leer únicamente
        | cuando existe un alumno autenticado.
        |
        */

        View::composer('*', function ($view): void {

            $user = auth()->user();

            $unreadNotificationsCount = 0;

            if (
                $user
                && $user->role === 'student'
            ) {
                $unreadNotificationsCount =
                    $user->systemNotifications()
                        ->whereNull('read_at')
                        ->count();
            }

            $view->with(
                'unreadNotificationsCount',
                $unreadNotificationsCount
            );
        });

    })
    ->create();