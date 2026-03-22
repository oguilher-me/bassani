<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_opportunity_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->default('#696cff'); // Default Primary
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('required_fields')->nullable(); // ['budget', 'measurement', 'project_pdf']
            $table->integer('probability_default')->default(0); // 0-100
            $table->timestamps();
        });

        // Seed initial default stages
        $stages = [
            ['name' => 'Sem contato', 'slug' => 'new', 'color' => '#8592a3', 'probability_default' => 0, 'order' => 1],
            ['name' => 'Identificação de interesse', 'slug' => 'qualification', 'color' => '#03c3ec', 'probability_default' => 10, 'order' => 2],
            ['name' => 'Apresentação', 'slug' => 'presentation', 'color' => '#696cff', 'probability_default' => 30, 'order' => 3],
            ['name' => 'Proposta enviada', 'slug' => 'proposal', 'color' => '#ffab00', 'probability_default' => 50, 'order' => 4],
            ['name' => 'Negociação', 'slug' => 'negotiation', 'color' => '#71dd37', 'probability_default' => 80, 'order' => 5],
            ['name' => 'Ganha', 'slug' => 'won', 'color' => '#23b7e5', 'probability_default' => 100, 'order' => 99],
            ['name' => 'Perdida', 'slug' => 'lost', 'color' => '#ff3e1d', 'probability_default' => 0, 'order' => 100],
        ];

        foreach ($stages as $stage) {
            DB::table('crm_opportunity_stages')->insert(array_merge($stage, [
                'created_at' => now(), 
                'updated_at' => now()
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_opportunity_stages');
    }
};
