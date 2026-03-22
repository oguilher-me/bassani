<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PipelineStage extends Model
{
    use HasFactory;

    protected $table = 'crm_pipeline_stages';

    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
        'probability',
        'is_active',
        'description',
        'required_actions'
    ];

    protected $casts = [
        'required_actions' => 'array',
        'is_active' => 'boolean',
        'probability' => 'integer',
        'order' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function opportunities()
    {
        // Linking via slug to maintain compatibility with existing 'stage_id' string column on Opportunity
        return $this->hasMany(CrmOpportunity::class, 'stage_id', 'slug');
    }
}
