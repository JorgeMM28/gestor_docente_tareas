<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $search = trim((string) $request->get('search', ''));

        $tasks = Task::query()
            ->withCount('submissions')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query
                    ->where('status', Task::STATUS_ACTIVE)
                    ->where('due_at', '>=', now());
            })
            ->when($status === 'expired', function ($query) {
                $query
                    ->where('status', Task::STATUS_ACTIVE)
                    ->where('due_at', '<', now());
            })
            ->when($status === 'cancelled', function ($query) {
                $query->where(
                    'status',
                    Task::STATUS_CANCELLED
                );
            })
            ->when(
                in_array($type, [
                    Task::TYPE_HOMEWORK,
                    Task::TYPE_CLASS_ACTIVITY,
                    Task::TYPE_PRESENTATION,
                ], true),
                function ($query) use ($type) {
                    $query->where('type', $type);
                }
            )
            ->latest('assigned_at')
            ->paginate(10)
            ->withQueryString();

        $activeStudentsCount = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->where('is_active', true)
            ->count();

        return view('teacher.tasks.index', compact(
            'tasks',
            'activeStudentsCount',
            'status',
            'type',
            'search'
        ));
    }

    public function create(): View
    {
        return view('teacher.tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'required',
                'string',
                'max:5000',
            ],
            'type' => [
                'required',
                Rule::in([
                    Task::TYPE_HOMEWORK,
                    Task::TYPE_CLASS_ACTIVITY,
                    Task::TYPE_PRESENTATION,
                ]),
            ],
            'due_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
        ], [
            'title.required' => 'El título de la tarea es obligatorio.',
            'description.required' =>
                'La descripción de la tarea es obligatoria.',
            'type.required' => 'Selecciona un tipo de tarea.',
            'type.in' => 'El tipo de tarea seleccionado no es válido.',
            'due_date.required' =>
                'Selecciona la fecha límite de entrega.',
            'due_date.date_format' =>
                'La fecha límite no tiene un formato válido.',
            'due_date.after_or_equal' =>
                'La fecha límite no puede ser anterior a hoy.',
        ]);

        $dueAt = now()
            ->parse($validated['due_date'])
            ->endOfDay();

        $task = DB::transaction(function () use (
            $validated,
            $dueAt
        ) {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'assigned_at' => now(),
                'due_at' => $dueAt,
                'status' => Task::STATUS_ACTIVE,
                'cancelled_at' => null,
            ]);

            $students = User::query()
                ->where('role', User::ROLE_STUDENT)
                ->where('is_active', true)
                ->get(['id']);

            foreach ($students as $student) {
                Notification::create([
                    'user_id' => $student->id,
                    'task_id' => $task->id,
                    'type' => Notification::TYPE_NEW_TASK,
                    'title' => 'Nueva tarea asignada',
                    'message' =>
                        'Se ha asignado la tarea "'
                        .$task->title
                        .'".',
                ]);
            }

            return $task;
        });

        return redirect()
            ->route('teacher.tasks.show', $task)
            ->with('success', 'Tarea creada correctamente.');
    }

    public function show(Task $task): View
    {
        $task->load([
            'submissions' => function ($query) {
                $query->with([
                    'student:id,name,email,enrollment_number,is_active',
                    'grade',
                ]);
            },
        ]);

        $students = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'enrollment_number',
                'is_active',
            ]);

        /*
         * Indexamos entregas por student_id para que
         * la vista pueda determinar rápidamente:
         *
         * entregó / pendiente / no entregó.
         */
        $submissionsByStudent = $task
            ->submissions
            ->keyBy('student_id');

        $submittedCount = $task->submissions->count();

        $activeStudentsCount = $students
            ->where('is_active', true)
            ->count();

        $pendingCount = max(
            0,
            $activeStudentsCount - $submittedCount
        );

        return view('teacher.tasks.show', compact(
            'task',
            'students',
            'submissionsByStudent',
            'submittedCount',
            'activeStudentsCount',
            'pendingCount'
        ));
    }

    public function cancel(Task $task): RedirectResponse
    {
        if ($task->isCancelled()) {
            return back()->with(
                'info',
                'La tarea ya se encuentra cancelada.'
            );
        }

        DB::transaction(function () use ($task) {
            $task->update([
                'status' => Task::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);

            $students = User::query()
                ->where('role', User::ROLE_STUDENT)
                ->where('is_active', true)
                ->get(['id']);

            foreach ($students as $student) {
                Notification::create([
                    'user_id' => $student->id,
                    'task_id' => $task->id,
                    'type' =>
                        Notification::TYPE_TASK_CANCELLED,
                    'title' => 'Tarea cancelada',
                    'message' =>
                        'La tarea "'
                        .$task->title
                        .'" ha sido cancelada.',
                ]);
            }
        });

        return back()->with(
            'success',
            'Tarea cancelada correctamente.'
        );
    }
}