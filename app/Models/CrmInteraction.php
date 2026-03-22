<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'interactive_id',
        'interactive_type',
        'user_id',
        'type',
        'medium',
        'date',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function interactive()
    {
        return $this->morphTo();
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(CrmOpportunity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
