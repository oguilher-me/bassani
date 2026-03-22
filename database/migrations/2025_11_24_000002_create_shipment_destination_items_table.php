<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipment_destination_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->unsignedBigInteger('sale_item_id');
            $table->decimal('quantity', 12, 3)->nullable();
            $table->timestamps();

            $table->foreign('destination_id')
                ->references('id')
                ->on('shipment_destinations')
                ->onDelete('cascade');
            $table->foreign('sale_item_id')
                ->references('id')
                ->on('sale_items')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_destination_items');
    }
};

