<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'version_number',
        'total_value',
        'discount_percent',
        'status',
        'project_files_path',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'project_files_path' => 'array',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(CrmOpportunity::class, 'opportunity_id');
    }
}
