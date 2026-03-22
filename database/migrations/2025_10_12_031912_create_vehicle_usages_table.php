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
        Schema::create('vehicle_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->dateTime('departure_date');
            $table->integer('departure_mileage');
            $table->dateTime('return_date')->nullable();
            $table->integer('return_mileage')->nullable();
            $table->string('route_destination')->nullable();
            $table->text('observations')->nullable();
            $table->string('trip_status')->default('Em andamento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_usages');
    }
};
