<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDestination extends Model
{
    protected $fillable = [
        'planned_shipment_id',
        'address',
        'contact_name',
        'contact_phone',
        'window_start',
        'window_end',
        'confirmation_status',
        'started_at',
        'start_photo_path',
        'start_latitude',
        'start_longitude',
        'start_accuracy',
        'finished_at',
        'finish_notes',
        'finish_photo_paths',
        'finish_pending_reason',
    ];

    protected $casts = [
        'window_start' => 'datetime',
        'window_end' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'finish_photo_paths' => 'array',
    ];

    public function plannedShipment()
    {
        return $this->belongsTo(PlannedShipment::class, 'planned_shipment_id');
    }

    public function items()
    {
        return $this->belongsToMany(SaleItem::class, 'shipment_destination_items', 'destination_id', 'sale_item_id');
    }
}
