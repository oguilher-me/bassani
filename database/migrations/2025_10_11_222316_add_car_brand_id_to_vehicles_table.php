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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('car_brand_id')->nullable()->constrained('car_brands')->onDelete('set null');
            $table->dropColumn('marca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('marca')->nullable(); // Revertendo a coluna marca
            $table->dropConstrainedForeignId('car_brand_id');
        });
    }
};
