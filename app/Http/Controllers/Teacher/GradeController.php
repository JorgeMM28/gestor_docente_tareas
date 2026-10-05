<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function edit(Task $task): View|RedirectResponse
    {
        if ($task->isCancelled()) {
            return redirect()
                ->route('teacher.tasks.show', $task)
                ->with(
                    'error',
                    'No se puede evaluar una tarea cancelada.'
                );
        }

        if (! $task->canBeGraded()) {
            return redirect()
                ->route('teacher.tasks.show', $task)
                ->with(
                    'error',
                    'La tarea solo puede evaluarse después de la fecha límite.'
                );
        }

        $task->load([
            'submissions' => function ($query) {
                $query
                    ->with([
                        'student:id,name,email,enrollment_number',
                        'grade',
                    ])
                    ->orderBy('submitted_at');
            },
        ]);

        return view('teacher.tasks.grade', compact('task'));
    }

    public function update(
        Request $request,
        Task $task
    ): RedirectResponse {
        if ($task->isCancelled()) {
            return back()->with(
                'error',
                'No se puede evaluar una tarea cancelada.'
            );
        }

        if (! $task->canBeGraded()) {
            return back()->with(
                'error',
                'La tarea todavía no puede evaluarse.'
            );
        }

        $validated = $request->validate([
            'submission_id' => [
                'required',
                'integer',
                Rule::exists('submissions', 'id')
                    ->where(
                        fn ($query) =>
                        $query->where('task_id', $task->id)
                    ),
            ],
            'score' => [
                'required',
                'numeric',
                'min:0',
                'max:10',
            ],
            'action' => [
                'required',
                Rule::in(['draft', 'publish']),
            ],
        ], [
            'submission_id.required' =>
                'La entrega es obligatoria.',
            'submission_id.exists' =>
                'La entrega seleccionada no pertenece a esta tarea.',
            'score.required' =>
                'Ingresa una calificación.',
            'score.numeric' =>
                'La calificación debe ser numérica.',
            'score.min' =>
                'La calificación mínima es 0.',
            'score.max' =>
                'La calificación máxima es 10.',
            'action.required' =>
                'Selecciona una acción válida.',
        ]);

        $submission = Submission::query()
            ->where('task_id', $task->id)
            ->findOrFail($validated['submission_id']);

        DB::transaction(function () use (
            $submission,
            $validated,
            $task
        ) {
            $status = $validated['action'] === 'publish'
                ? Grade::STATUS_PUBLISHED
                : Grade::STATUS_DRAFT;

            $existingGrade = Grade::query()
                ->where(
                    'submission_id',
                    $submission->id
                )
                ->first();

            $wasPublished = $existingGrade?->isPublished()
                ?? false;

            $grade = Grade::updateOrCreate(
                [
                    'submission_id' => $submission->id,
                ],
                [
                    'score' => $validated['score'],
                    'status' => $status,
                    'published_at' =>
                        $status === Grade::STATUS_PUBLISHED
                            ? now()
                            : null,
                ]
            );

            /*
             * Generamos la notificación cuando una
             * calificación pasa a publicada.
             *
             * Si ya estaba publicada y únicamente se
             * guarda nuevamente, evitamos duplicarla.
             */
            if (
                $grade->isPublished()
                && ! $wasPublished
            ) {
                Notification::create([
                    'user_id' => $submission->student_id,
                    'task_id' => $task->id,
                    'type' =>
                        Notification::TYPE_GRADE_PUBLISHED,
                    'title' => 'Calificación publicada',
                    'message' =>
                        'Tu calificación de la tarea "'
                        .$task->title
                        .'" ya está disponible.',
                ]);
            }
        });

        $message = $validated['action'] === 'publish'
            ? 'Calificación publicada correctamente.'
            : 'Calificación guardada como borrador.';

        return back()->with('success', $message);
    }

    public function publishAll(Task $task): RedirectResponse
    {
        if ($task->isCancelled()) {
            return back()->with(
                'error',
                'No se puede evaluar una tarea cancelada.'
            );
        }

        if (! $task->canBeGraded()) {
            return back()->with(
                'error',
                'La tarea todavía no puede evaluarse.'
            );
        }

        $draftGrades = Grade::query()
            ->whereHas('submission', function ($query) use ($task) {
                $query->where('task_id', $task->id);
            })
            ->where('status', Grade::STATUS_DRAFT)
            ->with('submission')
            ->get();

        if ($draftGrades->isEmpty()) {
            return back()->with(
                'info',
                'No hay calificaciones en borrador para publicar.'
            );
        }

        DB::transaction(function () use (
            $draftGrades,
            $task
        ) {
            foreach ($draftGrades as $grade) {
                $grade->update([
                    'status' => Grade::STATUS_PUBLISHED,
                    'published_at' => now(),
                ]);

                Notification::create([
                    'user_id' =>
                        $grade->submission->student_id,
                    'task_id' => $task->id,
                    'type' =>
                        Notification::TYPE_GRADE_PUBLISHED,
                    'title' => 'Calificación publicada',
                    'message' =>
                        'Tu calificación de la tarea "'
                        .$task->title
                        .'" ya está disponible.',
                ]);
            }
        });

        return back()->with(
            'success',
            'Todas las calificaciones en borrador fueron publicadas.'
        );
    }
}