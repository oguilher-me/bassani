<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'timestamp',
        'location',
        'latitude',
        'longitude',
        'remarks',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function plannedShipment(): BelongsTo
    {
        return $this->belongsTo(PlannedShipment::class, 'shipment_id');
    }
}