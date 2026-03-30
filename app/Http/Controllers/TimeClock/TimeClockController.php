<?php

namespace App\Http\Controllers\TimeClock;

use App\Http\Controllers\Controller;
use App\Models\TimeClock;
use App\Services\TimeClockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TimeClockController extends Controller
{
    public function __construct(
        private TimeClockService $timeClockService
    ) {}

    /**
     * Display the time clock interface for the assembler.
     */
    public function index(): View
    {
        $user = Auth::user();
        $todayClocks = $this->timeClockService->getTodayClocks($user);
        $workedHours = $this->timeClockService->calculateWorkedHours($user, now());
        $nextValidTypes = $this->timeClockService->getNextValidTypes($user);

        return view('timeclock.index', compact(
            'todayClocks',
            'workedHours',
            'nextValidTypes'
        ));
    }

    /**
     * Register a clock action.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:start,pause,resume,end',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $clock = $this->timeClockService->clockAction(
                user: Auth::user(),
                type: $request->type,
                latitude: $request->latitude,
                longitude: $request->longitude,
                deviceInfo: $request->userAgent(),
                notes: $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => $this->timeClockService->getTypeLabel($request->type) . ' registrado com sucesso!',
                'clock' => $clock->load('user'),
                'next_types' => $this->timeClockService->getNextValidTypes(Auth::user()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get today's clocks for the current user (API).
     */
    public function today(): JsonResponse
    {
        $user = Auth::user();
        $clocks = $this->timeClockService->getTodayClocks($user);
        $workedHours = $this->timeClockService->calculateWorkedHours($user, now());

        return response()->json([
            'clocks' => $clocks,
            'worked_hours' => $workedHours,
            'next_types' => $this->timeClockService->getNextValidTypes($user),
        ]);
    }
}
