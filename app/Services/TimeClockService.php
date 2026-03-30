<?php

namespace App\Services;

use App\Models\TimeClock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimeClockService
{
    /**
     * Valid clock type sequence.
     */
    private const VALID_SEQUENCES = [
        'start' => ['pause', 'end'],
        'pause' => ['resume'],
        'resume' => ['pause', 'end'],
        'end' => ['start'],
    ];

    /**
     * Clock in types for Spanish/Portuguese labels.
     */
    private const TYPE_LABELS = [
        'start' => 'Início do Expediente',
        'pause' => 'Pausa',
        'resume' => 'Retorno',
        'end' => 'Fim do Expediente',
    ];

    /**
     * Get the next valid clock types for a user.
     */
    public function getNextValidTypes(User $user): array
    {
        $lastClock = TimeClock::where('user_id', $user->id)
            ->orderBy('clock_in_at', 'desc')
            ->first();

        if (!$lastClock) {
            return ['start'];
        }

        return self::VALID_SEQUENCES[$lastClock->type] ?? ['start'];
    }

    /**
     * Validate if the clock action is allowed.
     *
     * @throws \Exception
     */
    public function validateClockAction(User $user, string $type): void
    {
        $lastClock = TimeClock::where('user_id', $user->id)
            ->orderBy('clock_in_at', 'desc')
            ->first();

        // If no previous clock, only 'start' is allowed
        if (!$lastClock && $type !== 'start') {
            throw new \Exception('Você precisa iniciar seu expediente primeiro.');
        }

        // If has previous clock, check sequence
        if ($lastClock) {
            $allowedNext = self::VALID_SEQUENCES[$lastClock->type] ?? [];

            if (!in_array($type, $allowedNext)) {
                $lastLabel = self::TYPE_LABELS[$lastClock->type];
                $allowedLabels = array_map(fn($t) => self::TYPE_LABELS[$t], $allowedNext);

                throw new \Exception(
                    "Ação inválida após '{$lastLabel}'. Próximas ações permitidas: " .
                    implode(', ', $allowedLabels)
                );
            }

            // Check if user has an open clock (start without end)
            if ($lastClock->type === 'start') {
                $hasOpenClock = !TimeClock::where('user_id', $user->id)
                    ->where('type', 'end')
                    ->whereDate('clock_in_at', $lastClock->clock_in_at)
                    ->exists();

                if ($hasOpenClock && $type !== 'pause' && $type !== 'end') {
                    throw new \Exception('Você já tem um expediente em andamento. Use pausa ou fim.');
                }
            }
        }

        // Validate sequence: cannot start if already started today without ending
        if ($type === 'start') {
            $hasStartedToday = TimeClock::where('user_id', $user->id)
                ->where('type', 'start')
                ->whereDate('clock_in_at', today())
                ->exists();

            $hasEndedToday = TimeClock::where('user_id', $user->id)
                ->where('type', 'end')
                ->whereDate('clock_in_at', today())
                ->exists();

            if ($hasStartedToday && !$hasEndedToday) {
                throw new \Exception('Você já iniciou seu expediente hoje. Use pausa ou fim.');
            }

            if ($hasStartedToday && $hasEndedToday) {
                // Allow starting again (multiple shifts)
            }
        }
    }

    /**
     * Register a clock action.
     */
    public function clockAction(
        User $user,
        string $type,
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $deviceInfo = null,
        ?string $notes = null
    ): TimeClock {
        $this->validateClockAction($user, $type);

        return TimeClock::create([
            'user_id' => $user->id,
            'type' => $type,
            'clock_in_at' => now(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'device_info' => $deviceInfo,
            'notes' => $notes,
        ]);
    }

    /**
     * Get today's clocks for a user.
     */
    public function getTodayClocks(User $user): Collection
    {
        return TimeClock::where('user_id', $user->id)
            ->whereDate('clock_in_at', today())
            ->orderBy('clock_in_at', 'asc')
            ->get();
    }

    /**
     * Calculate worked hours for a given day.
     */
    public function calculateWorkedHours(User $user, Carbon $date): array
    {
        $clocks = TimeClock::where('user_id', $user->id)
            ->whereDate('clock_in_at', $date)
            ->orderBy('clock_in_at', 'asc')
            ->get();

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

        $totalWorkedHours = floor($totalWorkedMinutes / 60);
        $totalWorkedMins = $totalWorkedMinutes % 60;

        $totalPausedHours = floor($totalPausedMinutes / 60);
        $totalPausedMins = $totalPausedMinutes % 60;

        return [
            'worked_minutes' => $totalWorkedMinutes,
            'worked_hours' => $totalWorkedHours,
            'worked_formatted' => sprintf('%02d:%02d', $totalWorkedHours, $totalWorkedMins),
            'paused_minutes' => $totalPausedMinutes,
            'paused_hours' => $totalPausedHours,
            'paused_formatted' => sprintf('%02d:%02d', $totalPausedHours, $totalPausedMins),
            'net_minutes' => $totalWorkedMinutes - $totalPausedMinutes,
            'net_formatted' => sprintf('%02d:%02d', 
                floor(($totalWorkedMinutes - $totalPausedMinutes) / 60),
                ($totalWorkedMinutes - $totalPausedMinutes) % 60
            ),
        ];
    }

    /**
     * Get clock mirror (espelho de ponto) for a user or all users.
     */
    public function getClockMirror(?int $userId = null, string $startDate, string $endDate): Collection
    {
        $query = TimeClock::with('user')
            ->betweenDates($startDate, $endDate)
            ->orderBy('clock_in_at', 'asc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get()
            ->groupBy('user_id')
            ->map(function ($clocks, $userId) {
                $user = $clocks->first()->user;
                $days = $clocks->groupBy(function ($clock) {
                    return $clock->clock_in_at->toDateString();
                });

                $dailySummary = $days->map(function ($dayClocks, $date) {
                    return [
                        'date' => $date,
                        'date_formatted' => Carbon::parse($date)->format('d/m/Y'),
                        'day_name' => Carbon::parse($date)->translatedFormat('D'),
                        'clocks' => $dayClocks,
                        'worked_hours' => $this->calculateDayWorkedHours($dayClocks),
                    ];
                });

                return [
                    'user' => $user,
                    'days' => $dailySummary,
                    'total_hours' => $dailySummary->sum('worked_hours.worked_minutes') / 60,
                ];
            });
    }

    /**
     * Calculate worked hours for a collection of clocks in a day.
     */
    private function calculateDayWorkedHours(Collection $clocks): array
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

    /**
     * Get the type label.
     */
    public function getTypeLabel(string $type): string
    {
        return self::TYPE_LABELS[$type] ?? $type;
    }
}
