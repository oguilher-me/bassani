<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDestinationEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'token',
        'nps_score',
        'comments',
        'photo_paths',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'photo_paths' => 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(ShipmentDestination::class, 'destination_id');
    }
}

