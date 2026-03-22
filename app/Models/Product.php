<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'unit_of_measure',
        'base_price',
        'gross_weight',
        'net_weight',
        'cubic_volume',
    ];
}
