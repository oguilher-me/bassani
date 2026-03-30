<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'driver_id',
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
        'date' => 'date',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(PlannedShipment::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            default => 'Pendente',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aprovado' => 'bg-label-success',
            'rejeitado' => 'bg-label-danger',
            default => 'bg-label-warning',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'Alimentação' => 'bx-restaurant',
            'Hospedagem' => 'bx-hotel',
            'Combustível' => 'bx-gas-pump',
            'Pedágio' => 'bx-transfer',
            'Estacionamento' => 'bx-car',
            'Material Extra' => 'bx-package',
            default => 'bx-receipt',
        };
    }
}
