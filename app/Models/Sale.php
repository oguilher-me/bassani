<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Sale extends Model implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'issue_date',
        'expected_delivery_date',
        'representative_id',
        'sales_responsible',
        'sales_division',
        'carrier_id',
        'payment_term_id',
        'currency',
        'contact_name',
        'contact_phone',
        'contact_email',
        'erp_code',
        'notes',
        'total_items',
        'total_discounts',
        'total_ipi',
        'total_icms_st',
        'shipping_cost',
        'grand_total',
        'total_weight',
        'total_volume',
        'total_packages',
        'order_status',
        'delivery_status',
        'shipping_method',
        'tracking_code',
        'payment_method',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'delivery_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'total_items' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_ipi' => 'decimal:2',
        'total_icms_st' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'total_volume' => 'decimal:2',
        'total_packages' => 'integer',
        'order_status' => \App\Enums\OrderStatusEnum::class,
        'payment_status' => \App\Enums\PaymentStatusEnum::class,
        'delivery_status' => \App\Enums\DeliveryStatusEnum::class,
        'shipping_method' => \App\Enums\ShippingMethodEnum::class,
        'payment_method' => \App\Enums\PaymentMethodEnum::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer->customer_type == 'PF' ? $this->customer->full_name : $this->customer->company_name;
    }

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function plannedShipments()
    {
        return $this->belongsToMany(PlannedShipment::class, 'shipment_sales', 'sale_id', 'shipment_id');
    }

    public function assemblySchedules()
    {
        return $this->hasMany(AssemblySchedule::class);
    }
}
