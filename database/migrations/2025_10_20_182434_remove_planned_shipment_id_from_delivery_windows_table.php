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
        Schema::table('delivery_windows', function (Blueprint $table) {
            $table->dropColumn('planned_shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_windows', function (Blueprint $table) {
            $table->foreignId('planned_shipment_id')->nullable()->constrained('planned_shipments')->onDelete('set null');
        });
    }
};
