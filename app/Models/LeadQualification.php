<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadQualification extends Model
{
    use HasFactory;

    protected $table = 'crm_lead_qualifications';

    protected $fillable = [
        'lead_id',
        'environments',
        'property_type',
        'estimated_investment',
        'urgency_level',
    ];

    protected $casts = [
        'environments' => 'array',
        'estimated_investment' => 'decimal:2',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
