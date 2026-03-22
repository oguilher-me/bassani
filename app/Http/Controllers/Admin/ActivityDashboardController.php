<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityDashboardController extends Controller
{
    /**
     * Display the CRM activity dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $targetUserId = $user->id;

        // Security & Filtering
        $isAdminOrManager = $user->hasRole('admin') || $user->hasRole('master') || $user->hasRole('manager');
        $users = [];

        if ($isAdminOrManager) {
            $users = User::orderBy('name')->get();
            if ($request->filled('user_id')) {
                $targetUserId = $request->user_id;
            }
        }

        $now = Carbon::now();
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        // 1. Today's Agenda (Scheduled for today)
        $todayAgenda = CrmActivity::with('opportunity')
            ->where('user_id', $targetUserId)
            ->where('status', 'pending')
            ->whereBetween('due_date', [$todayStart, $todayEnd])
            ->orderBy('due_date')
            ->get();

        // 2. Overdue Tasks (Pending and past due date)
        $overdueActivities = CrmActivity::with('opportunity')
            ->where('user_id', $targetUserId)
            ->where('status', 'pending')
            ->where('due_date', '<', $now)
            ->orderBy('due_date')
            ->get();

        // 3. Weekly Metrics (Completed this week)
        $weeklyMetrics = [
            'calls' => CrmActivity::where('user_id', $targetUserId)->where('type', 'call')->where('status', 'completed')->whereBetween('completed_at', [$weekStart, $weekEnd])->count(),
            'whatsapp' => CrmActivity::where('user_id', $targetUserId)->where('type', 'whatsapp')->where('status', 'completed')->whereBetween('completed_at', [$weekStart, $weekEnd])->count(),
            'meetings' => CrmActivity::where('user_id', $targetUserId)->where('type', 'meeting')->where('status', 'completed')->whereBetween('completed_at', [$weekStart, $weekEnd])->count(),
            'visits' => CrmActivity::where('user_id', $targetUserId)->where('type', 'visit')->where('status', 'completed')->whereBetween('completed_at', [$weekStart, $weekEnd])->count(),
            'tasks' => CrmActivity::where('user_id', $targetUserId)->where('type', 'task')->where('status', 'completed')->whereBetween('completed_at', [$weekStart, $weekEnd])->count(),
        ];
        
        $completedThisWeekCount = array_sum($weeklyMetrics);

        // 4. Kanban Data (Only for 'task' type)
        $kanbanTasks = [
            'pending' => CrmActivity::with('opportunity')->where('user_id', $targetUserId)->where('type', 'task')->where('status', 'pending')->where(function($q) use ($now) {
                $q->whereNull('due_date')->orWhere('due_date', '>=', $now);
            })->get(),
            'overdue' => $overdueActivities->where('type', 'task'), // Already fetched above
            'completed' => CrmActivity::with('opportunity')->where('user_id', $targetUserId)->where('type', 'task')->where('status', 'completed')->where('completed_at', '>=', Carbon::today()->subDays(7))->get(),
        ];

        // 5. Calendar Events (Meetings and Visits for the month)
        $calendarEvents = CrmActivity::where('user_id', $targetUserId)
            ->whereIn('type', ['meeting', 'visit'])
            ->whereBetween('due_date', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->endOfMonth()->addMonth()])
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'title' => ($activity->type === 'meeting' ? '📅 ' : '🚗 ') . $activity->subject,
                    'start' => $activity->due_date->toIso8601String(),
                    'url' => route('crm.opportunities.show', $activity->opportunity_id),
                    'className' => $activity->status === 'completed' ? 'bg-label-success' : ($activity->due_date->isPast() ? 'bg-label-danger' : 'bg-label-primary'),
                    'allDay' => false
                ];
            });

        return view('crm.activities.dashboard', compact(
            'todayAgenda', 
            'overdueActivities', 
            'weeklyMetrics', 
            'completedThisWeekCount',
            'kanbanTasks',
            'calendarEvents',
            'isAdminOrManager',
            'users',
            'targetUserId'
        ));
    }
}
