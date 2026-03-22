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
        Schema::create('assembly_schedule_item_assembler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('assembler_id')->constrained('users')->onDelete('cascade');
            $table->decimal('commission_value', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_schedule_item_assembler');
    }
};
