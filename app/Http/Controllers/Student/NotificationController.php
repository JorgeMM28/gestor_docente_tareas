<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Mostrar las notificaciones del alumno.
     */
    /**
 * Mostrar las notificaciones del alumno.
 */
public function index(): View
{
    $student = Auth::user();

    $unreadCount = Notification::query()
        ->where('user_id', $student->id)
        ->whereNull('read_at')
        ->count();

    $notifications = Notification::query()
        ->where('user_id', $student->id)
        ->with('task')
        ->latest()
        ->paginate(15);

    return view(
        'student.notifications.index',
        compact(
            'notifications',
            'unreadCount'
        )
    );
}

    /**
     * Abrir una notificación.
     *
     * La marca como leída y, si está relacionada con una tarea,
     * redirige a dicha tarea.
     */
    public function open(
        Notification $notification
    ): RedirectResponse {
        $this->ensureOwner($notification);

        if ($notification->read_at === null) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        if ($notification->task_id !== null) {
            return redirect()->route(
                'student.tasks.show',
                $notification->task_id
            );
        }

        return redirect()
            ->route('student.notifications.index');
    }

    /**
     * Marcar una notificación individual como leída.
     */
    public function read(
        Notification $notification
    ): RedirectResponse {
        $this->ensureOwner($notification);

        if ($notification->read_at === null) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        if ($notification->task_id !== null) {
            return redirect()->route(
                'student.tasks.show',
                $notification->task_id
            );
        }

        return redirect()
            ->route('student.notifications.index')
            ->with(
                'success',
                'Notificación marcada como leída.'
            );
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function readAll(): RedirectResponse
    {
        $student = Auth::user();

        Notification::query()
            ->where('user_id', $student->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return redirect()
            ->route('student.notifications.index')
            ->with(
                'success',
                'Todas las notificaciones fueron marcadas como leídas.'
            );
    }

    /**
     * Comprobar que la notificación pertenece
     * al alumno autenticado.
     */
    private function ensureOwner(
        Notification $notification
    ): void {
        if (
            (int) $notification->user_id
            !== (int) Auth::id()
        ) {
            abort(403);
        }
    }
}