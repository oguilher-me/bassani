<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_interactions', function (Blueprint $table) {
            // Add polymorphic columns
            $table->nullableMorphs('interactive');
            // Make opportunity_id nullable since it might be linked via morph or we migrate data
            $table->foreignId('opportunity_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('crm_interactions', function (Blueprint $table) {
            $table->dropMorphs('interactive');
            $table->foreignId('opportunity_id')->nullable(false)->change();
        });
    }
};
