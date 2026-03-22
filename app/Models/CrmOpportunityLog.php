<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmOpportunityLog extends Model
{
    public $timestamps = false; // Using created_at only (managed by DB or manually)

    protected $fillable = [
        'opportunity_id',
        'user_id',
        'action',
        'before',
        'after',
        'description',
        'duration_seconds',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $casts = [
        'before' => 'json',
        'after' => 'json',
        'created_at' => 'datetime'
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(CrmOpportunity::class, 'opportunity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
