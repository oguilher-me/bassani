<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Architect extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'document_type',
        'document_number',
        'specialty',
        'rt_percentage',
        'bank_data',
        'social_links',
        'status',
        'rating',
    ];

    protected $casts = [
        'bank_data' => 'array',
        'social_links' => 'array',
        'status' => 'boolean',
        'rt_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunities(): HasMany
    {
        // Assuming we will update CrmOpportunity to link to Architect model
        // Ideally this should be a polymorphic relation or we update the FK in opportunities table
        // For this step, I will assume we will switch the 'architect_id' FK reference in crm_opportunities
        // to point to 'architects' table instead of 'crm_entities'
        return $this->hasMany(CrmOpportunity::class, 'architect_id');
    }
}
