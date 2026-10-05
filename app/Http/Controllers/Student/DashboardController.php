<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Tareas activas
        |--------------------------------------------------------------------------
        |
        | Tareas que:
        | - No están canceladas.
        | - Todavía no han vencido.
        |
        */

        $activeTasks = Task::query()
            ->where('status', Task::STATUS_ACTIVE)
            ->where('due_at', '>=', now())
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Tareas pendientes
        |--------------------------------------------------------------------------
        |
        | Tareas activas que el alumno todavía no ha entregado.
        |
        */

        $pendingTasks = Task::query()
            ->where('status', Task::STATUS_ACTIVE)
            ->where('due_at', '>=', now())
            ->whereDoesntHave(
                'submissions',
                function ($query) use ($student) {
                    $query->where(
                        'student_id',
                        $student->id
                    );
                }
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Entregas realizadas
        |--------------------------------------------------------------------------
        */

        $submittedTasks = Submission::query()
            ->where(
                'student_id',
                $student->id
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Calificaciones publicadas
        |--------------------------------------------------------------------------
        |
        | Solamente contamos calificaciones que:
        | - Pertenecen a una entrega del alumno.
        | - Ya fueron publicadas por el docente.
        |
        */

        $gradedTasks = Grade::query()
            ->where(
                'status',
                Grade::STATUS_PUBLISHED
            )
            ->whereHas(
                'submission',
                function ($query) use ($student) {
                    $query->where(
                        'student_id',
                        $student->id
                    );
                }
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Próximas tareas
        |--------------------------------------------------------------------------
        |
        | Cargamos únicamente la entrega correspondiente al alumno
        | autenticado.
        |
        */

        $upcomingTasks = Task::query()
            ->where(
                'status',
                Task::STATUS_ACTIVE
            )
            ->where(
                'due_at',
                '>=',
                now()
            )
            ->with([
                'submissions' => function ($query) use ($student) {
                    $query
                        ->where(
                            'student_id',
                            $student->id
                        )
                        ->with('grade');
                }
            ])
            ->orderBy('due_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Notificaciones recientes
        |--------------------------------------------------------------------------
        */

        $recentNotifications = Notification::query()
            ->where(
                'user_id',
                $student->id
            )
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        return view(
            'student.dashboard',
            compact(
                'activeTasks',
                'pendingTasks',
                'submittedTasks',
                'gradedTasks',
                'upcomingTasks',
                'recentNotifications'
            )
        );
    }
}