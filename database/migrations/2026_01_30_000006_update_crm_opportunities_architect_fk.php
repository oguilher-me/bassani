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
        Schema::table('crm_opportunities', function (Blueprint $table) {
            // Drop the old FK constraint pointing to crm_entities
            // Constraint name usually follows table_column_foreign
            $table->dropForeign(['architect_id']);
            
            // Add new FK constraint pointing to architects table
            $table->foreign('architect_id')
                  ->references('id')
                  ->on('architects')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropForeign(['architect_id']);
            $table->foreign('architect_id')
                  ->references('id')
                  ->on('crm_entities')
                  ->nullOnDelete();
        });
    }
};
