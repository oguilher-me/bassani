<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeClock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'clock_in_at',
        'latitude',
        'longitude',
        'device_info',
        'notes',
    ];

    protected $casts = [
        'clock_in_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user that owns the time clock record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('clock_in_at', $date);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('clock_in_at', [$startDate, $endDate . ' 23:59:59']);
    }

    /**
     * Scope to get open clocks (start without end).
     */
    public function scopeOpen($query)
    {
        return $query->where('type', 'start')
            ->whereDoesntHave('closingRecord');
    }

    /**
     * Get the closing record for this clock (end record).
     */
    public function closingRecord()
    {
        return $this->hasOne(TimeClock::class, 'user_id', 'user_id')
            ->where('type', 'end')
            ->where('clock_in_at', '>', $this->clock_in_at)
            ->whereDate('clock_in_at', $this->clock_in_at->toDateString())
            ->orderBy('clock_in_at', 'asc')
            ->limit(1);
    }

    /**
     * Get the type label in Portuguese.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'start' => 'Início',
            'pause' => 'Pausa',
            'resume' => 'Retorno',
            'end' => 'Fim',
            default => $this->type
        };
    }

    /**
     * Get the type color for badges.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'start' => 'success',
            'pause' => 'warning',
            'resume' => 'info',
            'end' => 'danger',
            default => 'secondary'
        };
    }
}
