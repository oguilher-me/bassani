<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'is_restrictive',
        'status',
    ];

    protected $casts = [
        'is_restrictive' => 'boolean',
    ];

    /**
     * Get the checkup responses for this checklist item.
     */
    public function checkupResponses(): HasMany
    {
        return $this->hasMany(CheckupResponse::class);
    }

    /**
     * Scope to filter only active items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
