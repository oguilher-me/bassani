<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleFine extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'fine_number',
        'infraction_date',
        'notification_date',
        'due_date',
        'payment_date',
        'document_reference',
        'fine_type',
        'description',
        'location',
        'authority',
        'points',
        'fine_amount',
        'paid_amount',
        'payment_status',
        'responsible_for_payment',
        'document_reference',
        'comments',
    ];

    protected $casts = [
        'infraction_date' => 'date',
        'notification_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_status' => \App\Enums\PaymentStatus::class,
        'responsible_for_payment' => \App\Enums\ResponsibleForPayment::class,
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
