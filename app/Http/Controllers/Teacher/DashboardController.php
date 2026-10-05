<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->where('is_active', true)
            ->count();

        $totalTasks = Task::query()
            ->where('status', Task::STATUS_ACTIVE)
            ->count();

        $activeTasks = Task::query()
            ->where('status', Task::STATUS_ACTIVE)
            ->where('due_at', '>=', now())
            ->count();

        $expiredTasks = Task::query()
            ->where('status', Task::STATUS_ACTIVE)
            ->where('due_at', '<', now())
            ->count();

        $totalSubmissions = Submission::count();

        $recentTasks = Task::query()
            ->withCount('submissions')
            ->latest('assigned_at')
            ->limit(5)
            ->get();

        $recentSubmissions = Submission::query()
            ->with([
                'student:id,name,enrollment_number',
                'task:id,title',
            ])
            ->latest('submitted_at')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalTasks',
            'activeTasks',
            'expiredTasks',
            'totalSubmissions',
            'recentTasks',
            'recentSubmissions'
        ));
    }
}