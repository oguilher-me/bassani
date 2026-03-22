<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'full_name',
        'cpf',
        'cnh_number',
        'cnh_category',
        'cnh_expiration_date',
        'phone',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleUsages()
    {
        return $this->hasMany(\App\Models\VehicleUsage::class);
    }

    public function vehicleFines()
    {
        return $this->hasMany(\App\Models\VehicleFine::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\DriverDocument::class);
    }
}
