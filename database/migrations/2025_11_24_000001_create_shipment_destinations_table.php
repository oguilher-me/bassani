<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipment_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planned_shipment_id');
            $table->string('address');
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->dateTime('window_start')->nullable();
            $table->dateTime('window_end')->nullable();
            $table->timestamps();

            $table->foreign('planned_shipment_id')
                ->references('shipment_id')
                ->on('planned_shipments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_destinations');
    }
};

