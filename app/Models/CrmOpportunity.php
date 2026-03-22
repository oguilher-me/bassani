<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasInteractions;
use Illuminate\Support\Str;

class CrmOpportunity extends Model
{
    use HasFactory, HasInteractions;

    protected $fillable = [
        'uuid',
        'entity_id',
        'customer_id',
        'architect_id',
        'partner_id',
        'user_id',
        'title',
        'stage_id',
        'status',
        'probability',
        'estimated_value',
        'expected_closing_date',
        'loss_reason',
        'video_call_link',
        'cpf_cnpj',
        'address',
        'project_size',
        'needs_project_development',
        'project_deadline',
        'created_by',
        'seller_id',
        'owner_id'
    ];

    protected $casts = [
        'expected_closing_date' => 'date',
        'project_deadline' => 'date',
        'needs_project_development' => 'boolean',
        'estimated_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->created_by)) {
                $model->created_by = auth()->id();
            }
        });
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->orWhere('owner_id', $userId);
    }

    public function scopeBottleneck($query, $days = 10)
    {
        // Opportunities stuck in same stage for X days
        // Assuming updated_at reflects last stage change or we check history.
        // Simple approach: updated_at < X days ago AND status = open
        return $query->where('status', 'open')
                     ->where('updated_at', '<', now()->subDays($days));
    }

    // Relationships
    public function entity()
    {
        return $this->belongsTo(CrmEntity::class, 'entity_id');
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class, 'stage_id', 'slug');
    }

    public function architect()
    {
        return $this->belongsTo(Architect::class, 'architect_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function partner()
    {
        return $this->belongsTo(CrmEntity::class, 'partner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function logs()
    {
        return $this->hasMany(CrmOpportunityLog::class, 'opportunity_id')->orderBy('created_at', 'desc');
    }

    public function activities()
    {
        return $this->hasMany(CrmActivity::class, 'opportunity_id')->orderBy('created_at', 'desc');
    }

    public function attachments()
    {
        return $this->hasMany(CrmOpportunityAttachment::class, 'opportunity_id')->orderBy('created_at', 'desc');
    }

    public function proposals()
    {
        return $this->hasMany(CrmProposal::class, 'opportunity_id');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(CrmInteraction::class, 'opportunity_id');
    }

    public function history()
    {
        return $this->hasMany(CrmOpportunityHistory::class, 'opportunity_id')->orderBy('created_at', 'desc');
    }

    // Commission Calculation Logic
    public function calculateSalesCommission($percentage)
    {
        return $this->estimated_value * ($percentage / 100);
    }

    public function calculateArchitectRT()
    {
        return 0;
    }
}
