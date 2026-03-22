<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryWindow extends Model
{
    protected $fillable = [
        'planned_shipment_id',
        'start_time',
        'end_time',
        'status',
    ];

    public function plannedShipment(): BelongsTo
    {
        return $this->belongsTo(PlannedShipment::class);
    }
}
