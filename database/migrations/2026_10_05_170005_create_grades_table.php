<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                ->unique()
                ->constrained('submissions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
             * Calificación de 0.0 a 10.0.
             *
             * La validación exacta se realizará también
             * desde el controlador.
             */
            $table->decimal('score', 4, 2);

            /*
             * draft
             * published
             */
            $table->string('status', 20)
                ->default('draft');

            /*
             * NULL mientras sea borrador.
             */
            $table->dateTime('published_at')
                ->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};