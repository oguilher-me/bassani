<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmOpportunityHistory extends Model
{
    use HasFactory;

    protected $table = 'crm_opportunity_history';

    protected $fillable = [
        'opportunity_id',
        'from_stage_id',
        'to_stage_id',
        'user_id',
        'duration_in_days'
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(CrmOpportunity::class, 'opportunity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
