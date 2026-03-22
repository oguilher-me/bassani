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
        Schema::dropIfExists('crm_interactions');
        Schema::dropIfExists('crm_proposals');
        Schema::dropIfExists('crm_opportunities');
        Schema::dropIfExists('crm_entities');

        // CRM Entities (Leads, Clients, Architects, Partners)
        Schema::create('crm_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['lead', 'client', 'architect', 'partner']);
            $table->string('document')->nullable(); // CPF/CNPJ
            $table->enum('segment', ['residential', 'commercial', 'high_end']);
            $table->unsignedBigInteger('origin_id')->nullable(); // Can be linked to a marketing source table if exists
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // CRM Opportunities (Projects)
        Schema::create('crm_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('crm_entities')->cascadeOnDelete();
            $table->foreignId('architect_id')->nullable()->constrained('crm_entities')->nullOnDelete(); // Architect is also an entity
            $table->string('title');
            $table->string('stage_id')->default('new'); // Could be foreign key to 'crm_stages' if we want dynamic stages
            $table->integer('probability')->default(0);
            $table->decimal('estimated_value', 15, 2)->default(0);
            $table->date('expected_closing_date')->nullable();
            $table->text('loss_reason')->nullable();
            $table->timestamps();
        });

        // CRM Proposals (Quotes/Budgets)
        Schema::create('crm_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('crm_opportunities')->cascadeOnDelete();
            $table->integer('version_number')->default(1);
            $table->decimal('total_value', 15, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected'])->default('draft');
            $table->json('project_files_path')->nullable();
            $table->timestamps();
        });

        // CRM Interactions (Communication)
        Schema::create('crm_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('crm_opportunities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['briefing', 'call', 'visit', 'technical_measurement']);
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_interactions');
        Schema::dropIfExists('crm_proposals');
        Schema::dropIfExists('crm_opportunities');
        Schema::dropIfExists('crm_entities');
    }
};
