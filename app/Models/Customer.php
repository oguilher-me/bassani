<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Antonrom\ModelChangesHistory\Traits\HasChangesHistory;

class Customer extends Model
{
    use HasFactory;
    use HasChangesHistory;
    protected $fillable = [
        'customer_type',
        'full_name',
        'company_name',
        'cpf',
        'cnpj',
        'rg',
        'ie',
        'email',
        'phone',
        'address_street',
        'address_number',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zip_code',
        'address_type',
        'representative_name',
        'status',
    ];
}
