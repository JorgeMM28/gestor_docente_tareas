<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('task_id')
                ->nullable()
                ->constrained('tasks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
             * new_task
             * task_cancelled
             * grade_published
             */
            $table->string('type', 30);

            $table->string('title', 150);

            $table->text('message');

            $table->dateTime('read_at')
                ->nullable();

            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};