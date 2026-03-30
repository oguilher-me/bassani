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
        Schema::create('checkup_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkup_id')->constrained('vehicle_checkups')->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->cascadeOnDelete();
            $table->boolean('is_ok');
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkup_responses');
    }
};
