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
        Schema::create('crm_opportunity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('crm_opportunities')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // created, stage_change, seller_assigned, status_change, value_updated, etc.
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration_seconds')->nullable(); // Time spent in previous stage if action is stage_change
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_opportunity_logs');
    }
};
