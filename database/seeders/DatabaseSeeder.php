<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Docente
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Docente Administrador',
            'email' => 'docente@sistema.test',
            'enrollment_number' => null,
            'role' => User::ROLE_TEACHER,
            'is_active' => true,
            'password' => Hash::make('Docente123!'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Alumnos
        |--------------------------------------------------------------------------
        */

        $students = [
            [
                'name' => 'Ana Sofía Hernández',
                'email' => 'alumno01@sistema.test',
                'enrollment_number' => '20260001',
            ],
            [
                'name' => 'Carlos Eduardo Martínez',
                'email' => 'alumno02@sistema.test',
                'enrollment_number' => '20260002',
            ],
            [
                'name' => 'Daniela López García',
                'email' => 'alumno03@sistema.test',
                'enrollment_number' => '20260003',
            ],
            [
                'name' => 'Diego Alejandro Sánchez',
                'email' => 'alumno04@sistema.test',
                'enrollment_number' => '20260004',
            ],
            [
                'name' => 'Fernanda Ramírez Torres',
                'email' => 'alumno05@sistema.test',
                'enrollment_number' => '20260005',
            ],
            [
                'name' => 'Gabriel Ortiz Mendoza',
                'email' => 'alumno06@sistema.test',
                'enrollment_number' => '20260006',
            ],
            [
                'name' => 'Isabella Flores Reyes',
                'email' => 'alumno07@sistema.test',
                'enrollment_number' => '20260007',
            ],
            [
                'name' => 'Javier Morales Cruz',
                'email' => 'alumno08@sistema.test',
                'enrollment_number' => '20260008',
            ],
            [
                'name' => 'Karla Jiménez Romero',
                'email' => 'alumno09@sistema.test',
                'enrollment_number' => '20260009',
            ],
            [
                'name' => 'Luis Fernando Vargas',
                'email' => 'alumno10@sistema.test',
                'enrollment_number' => '20260010',
            ],
            [
                'name' => 'Mariana Castillo Vega',
                'email' => 'alumno11@sistema.test',
                'enrollment_number' => '20260011',
            ],
            [
                'name' => 'Nicolás Gómez Herrera',
                'email' => 'alumno12@sistema.test',
                'enrollment_number' => '20260012',
            ],
            [
                'name' => 'Paola Andrea Navarro',
                'email' => 'alumno13@sistema.test',
                'enrollment_number' => '20260013',
            ],
            [
                'name' => 'Ricardo Aguilar Moreno',
                'email' => 'alumno14@sistema.test',
                'enrollment_number' => '20260014',
            ],
            [
                'name' => 'Valeria Mendoza Ruiz',
                'email' => 'alumno15@sistema.test',
                'enrollment_number' => '20260015',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'enrollment_number' => $student['enrollment_number'],
                'role' => User::ROLE_STUDENT,
                'is_active' => true,
                'password' => Hash::make('Alumno123!'),
            ]);
        }
    }
}