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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['PF', 'PJ'])->default('PF');
            $table->string('document')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('city')->nullable();
            $table->string('uf', 2)->nullable();
            $table->enum('source', ['instagram', 'site', 'store', 'referral', 'architect', 'other'])->default('store');
            
            // FK to partners (using crm_entities table with type=partner)
            $table->foreignId('partner_id')->nullable()->constrained('crm_entities')->nullOnDelete();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Sales Rep
            
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'discarded'])->default('new');
            $table->text('discard_reason')->nullable();
            $table->timestamp('converted_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_lead_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->cascadeOnDelete();
            $table->json('environments')->nullable(); // cozinha, quarto, etc
            $table->enum('property_type', ['residential', 'commercial'])->default('residential');
            $table->decimal('estimated_investment', 15, 2)->nullable();
            $table->enum('urgency_level', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_lead_qualifications');
        Schema::dropIfExists('crm_leads');
    }
};
