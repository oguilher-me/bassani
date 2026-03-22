<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Seller extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'email',
        'photo',
        'user_id',
        'team_id',
        'phone',
        'commission_percentage',
        'status',
    ];

    /**
     * Relationship with User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Opportunities.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(CrmOpportunity::class, 'seller_id', 'user_id');
        // Note: Connecting via user_id since existing schema uses User IDs for assignment.
    }

    /**
     * Relationship with Leads.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'user_id', 'user_id');
    }

    /**
     * Calculated Attribute: Monthly Sales (Sum of 'won' opportunities this month).
     */
    public function getMonthlySalesAttribute()
    {
        return $this->opportunities()
            ->where('status', 'won')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('estimated_value');
    }

    /**
     * KPI: Conversion Rate (Won / Total)
     */
    public function getConversionRateAttribute()
    {
        $total = $this->opportunities()->count();
        if ($total === 0) return 0;

        $won = $this->opportunities()->where('status', 'won')->count();
        return ($won / $total) * 100;
    }

    /**
     * KPI: Average Ticket (Ticket Médio)
     */
    public function getAverageTicketAttribute()
    {
        $wonQuery = $this->opportunities()->where('status', 'won');
        $count = $wonQuery->count();
        if ($count === 0) return 0;

        return $wonQuery->sum('estimated_value') / $count;
    }

    /**
     * KPI: Open Leads Volume
     */
    public function getOpenLeadsCountAttribute()
    {
        return $this->leads()->where('status', '!=', 'converted')->count();
    }
}
