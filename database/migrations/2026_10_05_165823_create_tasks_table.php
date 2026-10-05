<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 150);

            $table->text('description');

            /*
             * Valores permitidos por la aplicación:
             *
             * homework
             * class_activity
             * presentation
             */
            $table->string('type', 30);

            /*
             * Momento en el que la tarea fue asignada/publicada.
             */
            $table->dateTime('assigned_at');

            /*
             * Fecha y hora límite.
             *
             * Ejemplo:
             * 2026-10-09 23:59:59
             */
            $table->dateTime('due_at');

            /*
             * active
             * cancelled
             */
            $table->string('status', 20)
                ->default('active');

            /*
             * Se conserva la fecha de cancelación
             * para mantener el historial.
             */
            $table->dateTime('cancelled_at')
                ->nullable();

            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};