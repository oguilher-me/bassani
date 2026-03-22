<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasInteractions;

class Lead extends Model
{
    use HasFactory, SoftDeletes, HasInteractions;

    protected $table = 'crm_leads';

    protected $fillable = [
        'name',
        'type',
        'document',
        'email',
        'phone',
        'whatsapp',
        'city',
        'uf',
        'source',
        'partner_id',
        'user_id',
        'status',
        'discard_reason',
        'converted_at',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
    ];

    public function qualification(): HasOne
    {
        return $this->hasOne(LeadQualification::class, 'lead_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(CrmEntity::class, 'partner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(CrmOpportunity::class, 'entity_id');
    }
}
