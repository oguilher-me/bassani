<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DriverDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'description',
        'file_path',
        'file_type',
        'category',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Check if document is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if document expires within 30 days.
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expires_at
            && !$this->expires_at->isPast()
            && $this->expires_at->diffInDays(Carbon::today()) <= 30;
    }
}
