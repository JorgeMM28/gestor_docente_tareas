<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Controladores del docente
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\TaskController as TeacherTaskController;
use App\Http\Controllers\Teacher\GradeController as TeacherGradeController;
use App\Http\Controllers\Teacher\SubmissionController as TeacherSubmissionController;

/*
|--------------------------------------------------------------------------
| Controladores del alumno
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\TaskController as StudentTaskController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;


/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    abort(403);

})->name('home');


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {

        /*
        |----------------------------------------------------------------------
        | Formulario de inicio de sesión
        |----------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [AuthController::class, 'showLogin']
        )->name('login');


        /*
        |----------------------------------------------------------------------
        | Procesar inicio de sesión
        |----------------------------------------------------------------------
        */

        Route::post(
            '/login',
            [AuthController::class, 'login']
        )->name('login.store');

    });


/*
|--------------------------------------------------------------------------
| Cerrar sesión
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| DOCENTE
|--------------------------------------------------------------------------
|
| Todas las rutas:
|
| /docente/...
|
| requieren:
|
| - Usuario autenticado
| - Rol teacher
|
*/

Route::prefix('docente')
    ->name('teacher.')
    ->middleware([
        'auth',
        'teacher',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [TeacherDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Alumnos
        |--------------------------------------------------------------------------
        */

        Route::prefix('alumnos')
            ->name('students.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Listado
                |------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [TeacherStudentController::class, 'index']
                )->name('index');


                /*
                |------------------------------------------------------------------
                | Importación masiva
                |------------------------------------------------------------------
                |
                | IMPORTANTE:
                |
                | El controlador utiliza importForm(), no showImport().
                |
                */

                Route::get(
                    '/importar',
                    [TeacherStudentController::class, 'importForm']
                )->name('import.form');


                Route::post(
                    '/importar',
                    [TeacherStudentController::class, 'import']
                )->name('import');


                /*
                |------------------------------------------------------------------
                | Crear alumno
                |------------------------------------------------------------------
                */

                Route::get(
                    '/crear',
                    [TeacherStudentController::class, 'create']
                )->name('create');


                Route::post(
                    '/',
                    [TeacherStudentController::class, 'store']
                )->name('store');


                /*
                |------------------------------------------------------------------
                | Editar alumno
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{student}/editar',
                    [TeacherStudentController::class, 'edit']
                )->name('edit');


                Route::put(
                    '/{student}',
                    [TeacherStudentController::class, 'update']
                )->name('update');


                /*
                |------------------------------------------------------------------
                | Activar / desactivar alumno
                |------------------------------------------------------------------
                */

                Route::patch(
                    '/{student}/estado',
                    [TeacherStudentController::class, 'toggleStatus']
                )->name('toggle-status');

            });


        /*
        |--------------------------------------------------------------------------
        | Tareas
        |--------------------------------------------------------------------------
        */

        Route::prefix('tareas')
            ->name('tasks.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Listado
                |------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [TeacherTaskController::class, 'index']
                )->name('index');


                /*
                |------------------------------------------------------------------
                | Crear tarea
                |------------------------------------------------------------------
                */

                Route::get(
                    '/crear',
                    [TeacherTaskController::class, 'create']
                )->name('create');


                Route::post(
                    '/',
                    [TeacherTaskController::class, 'store']
                )->name('store');


                /*
                |------------------------------------------------------------------
                | Ver tarea
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{task}',
                    [TeacherTaskController::class, 'show']
                )->name('show');


                /*
                |------------------------------------------------------------------
                | Cancelar tarea
                |------------------------------------------------------------------
                */

                Route::patch(
                    '/{task}/cancelar',
                    [TeacherTaskController::class, 'cancel']
                )->name('cancel');


                /*
                |------------------------------------------------------------------
                | Evaluar tarea
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{task}/evaluar',
                    [TeacherGradeController::class, 'edit']
                )->name('grade');


                /*
                |------------------------------------------------------------------
                | Guardar / publicar una calificación
                |------------------------------------------------------------------
                */

                Route::put(
                    '/{task}/calificaciones',
                    [TeacherGradeController::class, 'update']
                )->name('grades.update');


                /*
                |------------------------------------------------------------------
                | Publicar todas las calificaciones
                |------------------------------------------------------------------
                */

                Route::patch(
                    '/{task}/calificaciones/publicar',
                    [TeacherGradeController::class, 'publishAll']
                )->name('grades.publish-all');

            });


        /*
        |--------------------------------------------------------------------------
        | Evidencias de alumnos
        |--------------------------------------------------------------------------
        */

        Route::prefix('evidencias')
            ->name('submissions.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Visualizar PDF
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{submission}/ver',
                    [TeacherSubmissionController::class, 'view']
                )->name('view');


                /*
                |------------------------------------------------------------------
                | Descargar PDF
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{submission}/descargar',
                    [TeacherSubmissionController::class, 'download']
                )->name('download');

            });

    });


/*
|--------------------------------------------------------------------------
| ALUMNO
|--------------------------------------------------------------------------
|
| Todas las rutas:
|
| /alumno/...
|
| requieren:
|
| - Usuario autenticado
| - Rol student
|
*/

Route::prefix('alumno')
    ->name('student.')
    ->middleware([
        'auth',
        'student',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [StudentDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Tareas
        |--------------------------------------------------------------------------
        */

        Route::prefix('tareas')
            ->name('tasks.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Listado de tareas
                |------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [StudentTaskController::class, 'index']
                )->name('index');


                /*
                |------------------------------------------------------------------
                | Ver tarea
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{task}',
                    [StudentTaskController::class, 'show']
                )->name('show');


                /*
                |------------------------------------------------------------------
                | Subir / reemplazar evidencia
                |------------------------------------------------------------------
                */

                Route::post(
                    '/{task}/entregar',
                    [StudentSubmissionController::class, 'store']
                )->name('submit');

            });


        /*
        |--------------------------------------------------------------------------
        | Mis entregas
        |--------------------------------------------------------------------------
        |
        | El historial está implementado en:
        |
        | Student\TaskController::history()
        |
        */

        Route::prefix('entregas')
            ->name('submissions.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Historial de entregas
                |------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [StudentTaskController::class, 'history']
                )->name('index');


                /*
                |------------------------------------------------------------------
                | Visualizar evidencia
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{submission}/ver',
                    [StudentSubmissionController::class, 'view']
                )->name('view');


                /*
                |------------------------------------------------------------------
                | Descargar evidencia
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{submission}/descargar',
                    [StudentSubmissionController::class, 'download']
                )->name('download');

            });


        /*
        |--------------------------------------------------------------------------
        | Notificaciones
        |--------------------------------------------------------------------------
        */

        Route::prefix('notificaciones')
            ->name('notifications.')
            ->group(function () {

                /*
                |------------------------------------------------------------------
                | Listado
                |------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [StudentNotificationController::class, 'index']
                )->name('index');


                /*
                |------------------------------------------------------------------
                | Marcar todas como leídas
                |------------------------------------------------------------------
                |
                | Esta ruta debe estar ANTES de /{notification}/...
                |
                */

                Route::patch(
                    '/leer-todas',
                    [StudentNotificationController::class, 'readAll']
                )->name('read-all');


                /*
                |------------------------------------------------------------------
                | Abrir notificación
                |------------------------------------------------------------------
                */

                Route::get(
                    '/{notification}/abrir',
                    [StudentNotificationController::class, 'open']
                )->name('open');


                /*
                |------------------------------------------------------------------
                | Marcar individual como leída
                |------------------------------------------------------------------
                */

                Route::patch(
                    '/{notification}/leer',
                    [StudentNotificationController::class, 'read']
                )->name('read');

            });

    });