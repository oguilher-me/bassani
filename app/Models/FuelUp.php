<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelUp extends Model
{
    protected $fillable = [
        'vehicle_id',
        'fuel_up_date',
        'fuel_type',
        'quantity',
        'total_value',
        'unit_value',
        'current_km',
        'fuel_up_type',
        'station_name',
        'payment_method',
        'observations',
        'previous_km',
        'distance_traveled',
        'consumption_km_l',
        'cost_per_km',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
