<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'document_number',
        'supplier_type',
        'phone',
        'email',
        'contact_person',
        'address',
        'address_number',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'services_offered',
        'status',
        'documents',
    ];

    protected $casts = [
        'documents' => 'array',
    ];
}
