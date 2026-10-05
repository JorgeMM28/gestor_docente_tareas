<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function view(
        Submission $submission
    ): BinaryFileResponse {
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

    public function download(
        Submission $submission
    ): StreamedResponse {
        abort_unless(
            Storage::disk('local')->exists(
                $submission->file_path
            ),
            404,
            'El archivo de evidencia no existe.'
        );

        return Storage::disk('local')->download(
            $submission->file_path,
            $submission->original_filename
        );
    }
}