<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Usuarios
        |--------------------------------------------------------------------------
        |
        | Aquí se almacenan tanto el docente como los alumnos.
        |
        | role:
        | - teacher
        | - student
        |
        | enrollment_number:
        | - null para el docente
        | - matrícula para los alumnos
        |
        */

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);

            $table->string('email', 150)
                ->unique();

            $table->string('enrollment_number', 30)
                ->nullable()
                ->unique();

            $table->string('role', 20);

            $table->boolean('is_active')
                ->default(true);

            $table->string('password');

            $table->rememberToken();

            $table->timestamps();

            $table->index('role');
            $table->index('is_active');
        });


        /*
        |--------------------------------------------------------------------------
        | Recuperación de contraseña
        |--------------------------------------------------------------------------
        |
        | Aunque actualmente el sistema no incluye recuperación de contraseña,
        | dejamos la tabla estándar preparada.
        |
        */

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')
                ->primary();

            $table->string('token');

            $table->timestamp('created_at')
                ->nullable();
        });


        /*
        |--------------------------------------------------------------------------
        | Sesiones
        |--------------------------------------------------------------------------
        |
        | Necesaria porque estamos utilizando:
        |
        | SESSION_DRIVER=database
        |
        */

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')
                ->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index();

            $table->string('ip_address', 45)
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->text('payload');

            $table->integer('last_activity')
                ->index();
        });
    }


    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('users');
    }
};