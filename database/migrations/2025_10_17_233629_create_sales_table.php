<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->dateTime('issue_date');
            $table->dateTime('expected_delivery_date');
            $table->dateTime('actual_delivery_date')->nullable();
            $table->string('sales_responsible');
            $table->foreignId('representative_id')->constrained('representatives');
            $table->enum('sales_division', ['Retail', 'Wholesale', 'Corporate', 'Export']);
            $table->foreignId('carrier_id')->constrained('carriers');
            $table->foreignId('payment_term_id')->constrained('payment_terms');
            $table->string('currency')->default('BRL');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('purchase_order')->nullable();
            $table->text('notes')->nullable();
            $table->string('erp_code')->nullable();
            $table->decimal('total_items', 12, 2)->default(0.00);
            $table->decimal('total_discounts', 12, 2)->default(0.00);
            $table->decimal('total_freight', 12, 2)->default(0.00);
            $table->decimal('total_ipi', 12, 2)->default(0.00);
            $table->decimal('total_icms', 12, 2)->default(0.00);
            $table->decimal('total_icms_st', 12, 2)->default(0.00);
            $table->decimal('total_difal', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2)->default(0.00);
            $table->decimal('gross_weight', 10, 3)->default(0.000);
            $table->decimal('net_weight', 10, 3)->default(0.000);
            $table->decimal('cubic_volume', 10, 3)->default(0.000);
            $table->integer('packages')->default(0);
            $table->enum('order_status', ['Open', 'Partially Invoiced', 'Invoiced', 'Cancelled'])->default('Open');
            $table->enum('delivery_status', ['Pendente', 'Em Trânsito', 'Entregue', 'Devolvido'])->default('Pendente');
            $table->enum('shipping_method', ['Próprio', 'Terceirizado']);
            $table->string('tracking_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
