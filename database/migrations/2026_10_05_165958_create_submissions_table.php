<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
             * Ruta interna del archivo dentro de storage.
             */
            $table->string('file_path');

            /*
             * Nombre que tenía el PDF cuando el alumno
             * lo seleccionó.
             */
            $table->string('original_filename');

            /*
             * Tamaño en bytes.
             */
            $table->unsignedBigInteger('file_size');

            $table->dateTime('submitted_at');

            $table->timestamps();

            /*
             * Un alumno solamente puede tener
             * una entrega por tarea.
             */
            $table->unique(
                ['task_id', 'student_id'],
                'submissions_task_student_unique'
            );

            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};