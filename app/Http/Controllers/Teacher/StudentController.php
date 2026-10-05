<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', 'all');

        $students = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_number', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('teacher.students.index', compact(
            'students',
            'search',
            'status'
        ));
    }

    public function create(): View
    {
        return view('teacher.students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],
            'enrollment_number' => [
                'required',
                'string',
                'max:30',
                'unique:users,enrollment_number',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'El nombre del alumno es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'enrollment_number.required' => 'La matrícula es obligatoria.',
            'enrollment_number.unique' => 'La matrícula ya está registrada.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'enrollment_number' => $validated['enrollment_number'],
            'role' => User::ROLE_STUDENT,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('teacher.students.index')
            ->with('success', 'Alumno registrado correctamente.');
    }

    public function edit(User $student): View
    {
        $this->ensureStudent($student);

        return view('teacher.students.edit', compact('student'));
    }

    public function update(
        Request $request,
        User $student
    ): RedirectResponse {
        $this->ensureStudent($student);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($student->id),
            ],
            'enrollment_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'enrollment_number')->ignore($student->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'El nombre del alumno es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'enrollment_number.required' => 'La matrícula es obligatoria.',
            'enrollment_number.unique' => 'La matrícula ya está registrada.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'enrollment_number' => $validated['enrollment_number'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $student->update($data);

        return redirect()
            ->route('teacher.students.index')
            ->with('success', 'Datos del alumno actualizados correctamente.');
    }

    public function toggleStatus(User $student): RedirectResponse
    {
        $this->ensureStudent($student);

        $student->update([
            'is_active' => ! $student->is_active,
        ]);

        $message = $student->is_active
            ? 'Alumno activado correctamente.'
            : 'Alumno desactivado correctamente.';

        return back()->with('success', $message);
    }

    public function importForm(): View
    {
        return view('teacher.students.import');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'mimes:csv,txt',
                'max:2048',
            ],
        ], [
            'csv_file.required' => 'Selecciona un archivo CSV.',
            'csv_file.mimes' => 'El archivo debe ser CSV.',
            'csv_file.max' => 'El archivo CSV no debe superar 2 MB.',
        ]);

        $path = $request->file('csv_file')->getRealPath();

        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->withErrors([
                'csv_file' => 'No fue posible leer el archivo.',
            ]);
        }

        /*
         * Formato requerido:
         *
         * name,email,enrollment_number,password
         */

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return back()->withErrors([
                'csv_file' => 'El archivo CSV está vacío.',
            ]);
        }

        /*
         * Elimina BOM UTF-8 si existe.
         */
        if (isset($header[0])) {
            $header[0] = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                (string) $header[0]
            );
        }

        $header = array_map(
            fn ($value) => strtolower(trim((string) $value)),
            $header
        );

        $expectedHeader = [
            'name',
            'email',
            'enrollment_number',
            'password',
        ];

        if ($header !== $expectedHeader) {
            fclose($handle);

            return back()->withErrors([
                'csv_file' =>
                    'El encabezado debe ser exactamente: '
                    .'name,email,enrollment_number,password',
            ]);
        }

        $rows = [];
        $line = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $line++;

            /*
             * Ignorar líneas completamente vacías.
             */
            if (
                count($data) === 1
                && trim((string) ($data[0] ?? '')) === ''
            ) {
                continue;
            }

            if (count($data) !== 4) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "La fila {$line} no contiene exactamente 4 columnas.",
                ]);
            }

            $row = array_combine($expectedHeader, $data);

            if ($row === false) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' => "No fue posible procesar la fila {$line}.",
                ]);
            }

            $row = array_map(
                fn ($value) => trim((string) $value),
                $row
            );

            if (
                $row['name'] === ''
                || $row['email'] === ''
                || $row['enrollment_number'] === ''
                || $row['password'] === ''
            ) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "La fila {$line} contiene campos vacíos.",
                ]);
            }

            if (! filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "El correo de la fila {$line} no es válido.",
                ]);
            }

            if (mb_strlen($row['name']) > 120) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "El nombre de la fila {$line} supera 120 caracteres.",
                ]);
            }

            if (mb_strlen($row['email']) > 150) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "El correo de la fila {$line} supera 150 caracteres.",
                ]);
            }

            if (mb_strlen($row['enrollment_number']) > 30) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "La matrícula de la fila {$line} supera 30 caracteres.",
                ]);
            }

            if (mb_strlen($row['password']) < 8) {
                fclose($handle);

                return back()->withErrors([
                    'csv_file' =>
                        "La contraseña de la fila {$line} debe tener al menos 8 caracteres.",
                ]);
            }

            $row['email'] = strtolower($row['email']);
            $row['_line'] = $line;

            $rows[] = $row;
        }

        fclose($handle);

        if (empty($rows)) {
            return back()->withErrors([
                'csv_file' => 'El archivo no contiene alumnos.',
            ]);
        }

        /*
         * Verificar duplicados dentro del propio CSV.
         */
        $emails = array_column($rows, 'email');
        $enrollmentNumbers = array_column(
            $rows,
            'enrollment_number'
        );

        if (count($emails) !== count(array_unique($emails))) {
            return back()->withErrors([
                'csv_file' =>
                    'Existen correos electrónicos duplicados dentro del CSV.',
            ]);
        }

        if (
            count($enrollmentNumbers)
            !== count(array_unique($enrollmentNumbers))
        ) {
            return back()->withErrors([
                'csv_file' =>
                    'Existen matrículas duplicadas dentro del CSV.',
            ]);
        }

        /*
         * Verificar registros existentes.
         */
        $existingEmail = User::query()
            ->whereIn('email', $emails)
            ->value('email');

        if ($existingEmail !== null) {
            return back()->withErrors([
                'csv_file' =>
                    "El correo {$existingEmail} ya está registrado.",
            ]);
        }

        $existingEnrollment = User::query()
            ->whereIn(
                'enrollment_number',
                $enrollmentNumbers
            )
            ->value('enrollment_number');

        if ($existingEnrollment !== null) {
            return back()->withErrors([
                'csv_file' =>
                    "La matrícula {$existingEnrollment} ya está registrada.",
            ]);
        }

        try {
            DB::transaction(function () use ($rows) {
                foreach ($rows as $row) {
                    User::create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'enrollment_number' =>
                            $row['enrollment_number'],
                        'role' => User::ROLE_STUDENT,
                        'is_active' => true,
                        'password' => Hash::make(
                            $row['password']
                        ),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'csv_file' =>
                    'Ocurrió un error durante la importación. '
                    .'No se registró ningún alumno.',
            ]);
        }

        return redirect()
            ->route('teacher.students.index')
            ->with(
                'success',
                count($rows).' alumnos importados correctamente.'
            );
    }

    private function ensureStudent(User $student): void
    {
        abort_unless(
            $student->role === User::ROLE_STUDENT,
            404
        );
    }
}