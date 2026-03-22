<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OpportunityStage extends Model
{
    use HasFactory;

    protected $table = 'crm_opportunity_stages';

    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
        'is_active',
        'required_fields',
        'probability_default'
    ];

    protected $casts = [
        'required_fields' => 'array',
        'is_active' => 'boolean',
        'probability_default' => 'integer',
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
        // Assuming opportunities link via 'stage_id' linked to 'slug' currently or we migrate to ID? 
        // Current implementation uses 'slug' (new, qualification, etc) stored in 'stage_id' string column.
        // Or did I change it?
        // Checking CrmOpportunity model... 'stage_id' is used.
        // Ideally we should relate via ID, but for backward compatibility with the current string-based system, 
        // we might relate via slug OR we migrate opportunities to use ID.
        // User asked to "Ajuste/Crie a tabela de estágios...".
        // Let's assume for now the relationship is via 'slug' on the opportunity table matching 'slug' on this table,
        // OR we should have the opportunity store the stage ID.
        // Given existing code uses strings, a migration to ID would be big.
        // Recommendation: Keep Opportunity.stage_id as string (slug) but manage via this table.
        // Relationship: hasMany(CrmOpportunity::class, 'stage_id', 'slug');
        
        return $this->hasMany(CrmOpportunity::class, 'stage_id', 'slug');
    }
}
