<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('entity_id')->constrained('crm_leads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
            $table->dropColumn('lead_id');
        });
    }
};
