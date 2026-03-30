<?php

namespace App\Http\Controllers\TimeClock;

use App\Http\Controllers\Controller;
use App\Models\TimeClock;
use App\Models\User;
use App\Services\TimeClockService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTimeClockController extends Controller
{
    public function __construct(
        private TimeClockService $timeClockService
    ) {}

    /**
     * Display the time clock mirror (espelho de ponto) for all assemblers.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfWeek()->format('Y-m-d'));
        $userId = $request->input('user_id');

        // Get assemblers (users with Montador role)
        $assemblers = User::whereHas('role', function ($q) {
            $q->where('name', 'Montador');
        })->orWhereHas('assembler')->get();

        $clockMirror = $this->timeClockService->getClockMirror($userId, $startDate, $endDate);

        // Calculate totals
        $totalHours = $clockMirror->sum('total_hours');
        $totalEmployees = $clockMirror->count();

        return view('admin.timeclock.index', compact(
            'clockMirror',
            'assemblers',
            'startDate',
            'endDate',
            'userId',
            'totalHours',
            'totalEmployees'
        ));
    }

    /**
     * Show details for a specific employee's clock records.
     */
    public function show(Request $request, int $userId): View
    {
        $startDate = $request->input('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfWeek()->format('Y-m-d'));

        $user = User::findOrFail($userId);
        $clocks = TimeClock::with('user')
            ->where('user_id', $userId)
            ->betweenDates($startDate, $endDate)
            ->orderBy('clock_in_at', 'asc')
            ->get();

        // Group by date for display
        $clocksByDate = $clocks->groupBy(function ($clock) {
            return $clock->clock_in_at->toDateString();
        });

        $dailySummary = $clocksByDate->map(function ($dayClocks, $date) {
            return [
                'date' => $date,
                'date_formatted' => \Carbon\Carbon::parse($date)->format('d/m/Y'),
                'day_name' => \Carbon\Carbon::parse($date)->translatedFormat('l'),
                'clocks' => $dayClocks,
                'worked_hours' => $this->calculateDayWorkedHours($dayClocks),
            ];
        });

        $totalHours = $dailySummary->sum('worked_hours.worked_minutes') / 60;

        return view('admin.timeclock.show', compact(
            'user',
            'dailySummary',
            'totalHours',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Calculate worked hours for a collection of clocks in a day.
     */
    private function calculateDayWorkedHours($clocks): array
    {
        $totalWorkedMinutes = 0;
        $totalPausedMinutes = 0;
        $lastStart = null;
        $lastPause = null;

        foreach ($clocks as $clock) {
            switch ($clock->type) {
                case 'start':
                    $lastStart = $clock->clock_in_at;
                    break;
                case 'pause':
                    if ($lastStart) {
                        $totalWorkedMinutes += $lastStart->diffInMinutes($clock->clock_in_at);
                        $lastStart = null;
                    }
                    $lastPause = $clock->clock_in_at;
                    break;
                case 'resume':
                    if ($lastPause) {
                        $totalPausedMinutes += $lastPause->diffInMinutes($clock->clock_in_at);
                    }
                    $lastStart = $clock->clock_in_at;
                    $lastPause = null;
                    break;
                case 'end':
                    if ($lastStart) {
                        $totalWorkedMinutes += $lastStart->diffInMinutes($clock->clock_in_at);
                        $lastStart = null;
                    }
                    break;
            }
        }

        $workedHours = floor($totalWorkedMinutes / 60);
        $workedMins = $totalWorkedMinutes % 60;

        return [
            'worked_minutes' => $totalWorkedMinutes,
            'worked_formatted' => sprintf('%02d:%02d', $workedHours, $workedMins),
            'paused_minutes' => $totalPausedMinutes,
        ];
    }
}
