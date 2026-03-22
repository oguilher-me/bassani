<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryWindow;

class PlannedShipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'shipment_id';

    public function getRouteKeyName()
    {
        return 'shipment_id';
    }

    protected $fillable = [
        'shipment_number',
        'vehicle_id',
        'driver_id',
        'planned_departure_date',
        'actual_departure_date',
        'planned_delivery_date',
        'actual_delivery_date',
        'status',
        'total_weight',
        'total_volume',
        'total_orders',
        'total_invoices',
        'delivery_window_start',
        'delivery_window_end',
        'destination_address',
        'remarks',
    ];

    protected $casts = [
        'planned_departure_date' => 'date',
        'actual_departure_date' => 'date',
        'planned_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'delivery_window_start' => 'datetime',
        'delivery_window_end' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'shipment_sales', 'shipment_id', 'sale_id');
    }

    public function saleItems()
    {
        return $this->belongsToMany(SaleItem::class, 'shipment_sale_items', 'shipment_id', 'sale_item_id');
    }


    public function tracking()
    {
        return $this->hasMany(ShipmentTracking::class, 'shipment_id');
    }

    public function deliveryWindows()
    {
        return $this->hasMany(DeliveryWindow::class);
    }

    public function destinations()
    {
        return $this->hasMany(ShipmentDestination::class, 'planned_shipment_id');
    }
}
