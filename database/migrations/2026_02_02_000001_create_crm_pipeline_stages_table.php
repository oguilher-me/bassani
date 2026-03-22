<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cleanup old table if exists from previous attempt or refactor
        if (Schema::hasTable('crm_opportunity_stages')) {
            Schema::rename('crm_opportunity_stages', 'crm_pipeline_stages');
            
            Schema::table('crm_pipeline_stages', function (Blueprint $table) {
                if (!Schema::hasColumn('crm_pipeline_stages', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('crm_pipeline_stages', 'required_actions')) {
                    $table->json('required_actions')->nullable();
                }
                if (Schema::hasColumn('crm_pipeline_stages', 'required_fields')) {
                    $table->dropColumn('required_fields');
                }
                if (Schema::hasColumn('crm_pipeline_stages', 'probability_default')) {
                    $table->renameColumn('probability_default', 'probability');
                }
            });
        } else {
            if (!Schema::hasTable('crm_pipeline_stages')) {
                Schema::create('crm_pipeline_stages', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->string('color')->default('#696cff');
                    $table->integer('order')->default(0);
                    $table->integer('probability')->default(0);
                    $table->boolean('is_active')->default(true);
                    
                    $table->text('description')->nullable();
                    $table->json('required_actions')->nullable();
                    
                    $table->timestamps();
                });

                // Seed default stages
                $stages = [
                    ['name' => 'Sem contato', 'slug' => 'new', 'color' => '#8592a3', 'probability' => 0, 'order' => 1, 'is_active' => true],
                    ['name' => 'Identificação de interesse', 'slug' => 'qualification', 'color' => '#03c3ec', 'probability' => 10, 'order' => 2, 'is_active' => true],
                    ['name' => 'Apresentação', 'slug' => 'presentation', 'color' => '#696cff', 'probability' => 30, 'order' => 3, 'is_active' => true],
                    ['name' => 'Proposta enviada', 'slug' => 'proposal', 'color' => '#ffab00', 'probability' => 50, 'order' => 4, 'is_active' => true],
                    ['name' => 'Negociação', 'slug' => 'negotiation', 'color' => '#71dd37', 'probability' => 80, 'order' => 5, 'is_active' => true],
                    ['name' => 'Ganha', 'slug' => 'won', 'color' => '#23b7e5', 'probability' => 100, 'order' => 99, 'is_active' => true],
                    ['name' => 'Perdida', 'slug' => 'lost', 'color' => '#ff3e1d', 'probability' => 0, 'order' => 100, 'is_active' => true],
                ];

                foreach ($stages as $stage) {
                    DB::table('crm_pipeline_stages')->insert(array_merge($stage, [
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]));
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_pipeline_stages');
    }
};
