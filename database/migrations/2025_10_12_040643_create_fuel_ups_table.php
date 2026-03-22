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
        Schema::create('fuel_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->dateTime('fuel_up_date');
            $table->string('fuel_type');
            $table->decimal('quantity', 8, 2);
            $table->decimal('total_value', 8, 2);
            $table->decimal('unit_value', 8, 2)->nullable(); // Calculado automaticamente
            $table->integer('current_km');
            $table->string('fuel_up_type');
            $table->string('station_name')->nullable();
            $table->string('payment_method');
            $table->text('observations')->nullable();
            $table->integer('previous_km')->nullable(); // Para cálculo de consumo
            $table->decimal('distance_traveled', 8, 2)->nullable(); // Calculado
            $table->decimal('consumption_km_l', 8, 2)->nullable(); // Calculado
            $table->decimal('cost_per_km', 8, 2)->nullable(); // Calculado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_ups');
    }
};
