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
        Schema::create('planned_shipments', function (Blueprint $table) {
            $table->id('shipment_id');
            $table->string('shipment_number', 50)->unique();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->date('planned_departure_date');
            $table->date('actual_departure_date')->nullable();
            $table->date('planned_delivery_date');
            $table->date('actual_delivery_date')->nullable();
            $table->enum('status', ['Planned', 'In Transit', 'Delivered', 'Returned', 'Cancelled'])->default('Planned');
            $table->decimal('total_weight', 10, 2)->nullable();
            $table->decimal('total_volume', 10, 2)->nullable();
            $table->integer('total_orders')->default(0);
            $table->integer('total_invoices')->default(0);
            $table->dateTime('delivery_window_start')->nullable();
            $table->dateTime('delivery_window_end')->nullable();
            $table->string('destination_address', 255)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_shipments');
    }
};
