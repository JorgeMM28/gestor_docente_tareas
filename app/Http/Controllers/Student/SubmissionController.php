<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class SubmissionController extends Controller
{
    public function store(
        Request $request,
        Task $task
    ): RedirectResponse {
        $student = $request->user();

        if (! $task->canSubmit()) {
            return back()->with(
                'error',
                'El periodo de entrega de esta tarea ha finalizado o la tarea fue cancelada.'
            );
        }

        $validated = $request->validate([
            'evidence' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:2048',
            ],
        ], [
            'evidence.required' =>
                'Selecciona un archivo PDF.',
            'evidence.file' =>
                'La evidencia seleccionada no es válida.',
            'evidence.mimes' =>
                'La evidencia debe ser un archivo PDF.',
            'evidence.mimetypes' =>
                'La evidencia debe ser un archivo PDF válido.',
            'evidence.max' =>
                'El archivo no puede superar 2 MB.',
        ]);

        $file = $validated['evidence'];

        $existingSubmission = Submission::query()
            ->where('task_id', $task->id)
            ->where('student_id', $student->id)
            ->first();

        /*
         * Nombre generado por el servidor.
         *
         * No utilizamos directamente el nombre original
         * como ruta física.
         */
        $filename = Str::uuid().'.pdf';

        $directory =
            'evidence/task_'.$task->id
            .'/student_'.$student->id;

        $path = $file->storeAs(
            $directory,
            $filename,
            'local'
        );

        if ($path === false) {
            return back()->with(
                'error',
                'No fue posible guardar el archivo.'
            );
        }

        try {
            DB::transaction(function () use (
                $task,
                $student,
                $file,
                $path,
                $existingSubmission
            ) {
                if ($existingSubmission) {
                    $oldPath = $existingSubmission->file_path;

                    $existingSubmission->update([
                        'file_path' => $path,
                        'original_filename' =>
                            $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'submitted_at' => now(),
                    ]);

                    /*
                     * Si por alguna razón existiera una
                     * calificación asociada, la eliminamos
                     * al reemplazar la evidencia.
                     *
                     * En condiciones normales esto no ocurre
                     * porque las calificaciones solo pueden
                     * registrarse después del vencimiento.
                     */
                    $existingSubmission->grade()?->delete();

                    if (
                        $oldPath !== $path
                        && Storage::disk('local')->exists(
                            $oldPath
                        )
                    ) {
                        Storage::disk('local')->delete(
                            $oldPath
                        );
                    }
                } else {
                    Submission::create([
                        'task_id' => $task->id,
                        'student_id' => $student->id,
                        'file_path' => $path,
                        'original_filename' =>
                            $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'submitted_at' => now(),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            if (
                Storage::disk('local')->exists($path)
            ) {
                Storage::disk('local')->delete($path);
            }

            report($exception);

            return back()->with(
                'error',
                'Ocurrió un error al registrar la entrega.'
            );
        }

        $message = $existingSubmission
            ? 'Evidencia reemplazada correctamente.'
            : 'Evidencia entregada correctamente.';

        return back()->with('success', $message);
    }

    public function view(
        Request $request,
        Submission $submission
    ): BinaryFileResponse {
        $student = $request->user();

        abort_unless(
            $submission->student_id === $student->id,
            403,
            'No tienes permiso para visualizar esta evidencia.'
        );

        abort_unless(
            Storage::disk('local')->exists(
                $submission->file_path
            ),
            404,
            'El archivo de evidencia no existe.'
        );

        $path = Storage::disk('local')->path(
            $submission->file_path
        );

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>
                'inline; filename="'
                .addslashes($submission->original_filename)
                .'"',
        ]);
    }
}