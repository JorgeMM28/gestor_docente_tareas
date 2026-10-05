<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Mostrar todas las tareas disponibles para el alumno.
     */
    public function index(Request $request): View
    {
        $student = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Filtros
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', 'all');
        $type = (string) $request->get('type', 'all');


        /*
        |--------------------------------------------------------------------------
        | Consulta base
        |--------------------------------------------------------------------------
        |
        | Cargamos únicamente la entrega perteneciente al alumno autenticado.
        | De esta manera:
        |
        | $task->submissions->first()
        |
        | siempre corresponderá al alumno actual.
        |
        */

        $query = Task::query()
            ->with([
                'submissions' => function ($query) use ($student) {
                    $query
                        ->where(
                            'student_id',
                            $student->id
                        )
                        ->with('grade');
                }
            ]);


        /*
        |--------------------------------------------------------------------------
        | Búsqueda
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where(
                        'title',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Tipo de tarea
        |--------------------------------------------------------------------------
        */

        if (
            $type !== 'all'
            && in_array(
                $type,
                [
                    Task::TYPE_HOMEWORK,
                    Task::TYPE_CLASS_ACTIVITY,
                    Task::TYPE_PRESENTATION,
                ],
                true
            )
        ) {
            $query->where('type', $type);
        }


        /*
        |--------------------------------------------------------------------------
        | Estado
        |--------------------------------------------------------------------------
        */

        switch ($status) {

            /*
             * Tareas activas que todavía no ha entregado.
             */
            case 'pending':

                $query
                    ->where(
                        'status',
                        Task::STATUS_ACTIVE
                    )
                    ->where(
                        'due_at',
                        '>=',
                        now()
                    )
                    ->whereDoesntHave(
                        'submissions',
                        function ($query) use ($student) {
                            $query->where(
                                'student_id',
                                $student->id
                            );
                        }
                    );

                break;


            /*
             * Tareas que el alumno ya entregó.
             */
            case 'submitted':

                $query->whereHas(
                    'submissions',
                    function ($query) use ($student) {
                        $query->where(
                            'student_id',
                            $student->id
                        );
                    }
                );

                break;


            /*
             * Tareas cuya fecha límite ya terminó.
             */
            case 'expired':

                $query
                    ->where(
                        'status',
                        Task::STATUS_ACTIVE
                    )
                    ->where(
                        'due_at',
                        '<',
                        now()
                    );

                break;


            /*
             * Tareas canceladas por el docente.
             */
            case 'cancelled':

                $query->where(
                    'status',
                    Task::STATUS_CANCELLED
                );

                break;


            /*
             * Todas las tareas.
             */
            case 'all':
            default:
                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Orden y paginación
        |--------------------------------------------------------------------------
        */

        $tasks = $query
            ->orderByDesc('assigned_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        return view(
            'student.tasks.index',
            compact(
                'tasks',
                'search',
                'status',
                'type'
            )
        );
    }


    /**
     * Mostrar una tarea específica.
     */
    public function show(Task $task): View
    {
        $student = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Entrega del alumno
        |--------------------------------------------------------------------------
        */

        $submission = Submission::query()
            ->where(
                'task_id',
                $task->id
            )
            ->where(
                'student_id',
                $student->id
            )
            ->with('grade')
            ->first();


        return view(
            'student.tasks.show',
            compact(
                'task',
                'submission'
            )
        );
    }


    /**
     * Historial de entregas del alumno.
     */
    public function history(): View
    {
        $student = Auth::user();

        $submissions = Submission::query()
            ->where(
                'student_id',
                $student->id
            )
            ->with([
                'task',
                'grade',
            ])
            ->orderByDesc('submitted_at')
            ->paginate(10);

        return view(
            'student.submissions.index',
            compact('submissions')
        );
    }
}