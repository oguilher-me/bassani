<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CrmActivity extends Model
{
    protected $fillable = [
        'opportunity_id',
        'user_id',
        'type',
        'subject',
        'description',
        'due_date',
        'completed_at',
        'status'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Scope for pending activities.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for overdue activities.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(CrmOpportunity::class, 'opportunity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
