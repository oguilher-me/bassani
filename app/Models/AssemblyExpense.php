<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssemblyExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'assembly_schedule_id',
        'assembler_id',
        'category',
        'amount',
        'description',
        'date',
        'receipt_path',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function assemblySchedule(): BelongsTo
    {
        return $this->belongsTo(AssemblySchedule::class);
    }

    public function assembler(): BelongsTo
    {
        return $this->belongsTo(Assembler::class);
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejeitado');
    }

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Human readable status label for UI badges.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aprovado'  => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            default     => 'Pendente',
        };
    }

    /**
     * Bootstrap class for status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aprovado'  => 'bg-label-success',
            'rejeitado' => 'bg-label-danger',
            default     => 'bg-label-warning',
        };
    }

    /**
     * Category icon (Boxicons).
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'Alimentação'   => 'bx-restaurant',
            'Hospedagem'    => 'bx-hotel',
            'Combustível'   => 'bxs-gas-pump',
            'Pedágio'       => 'bx-transfer',
            'Estacionamento'=> 'bx-car',
            'Material Extra'=> 'bx-package',
            default         => 'bx-receipt',
        };
    }
}
