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
            $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->after('seller_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['created_by', 'seller_id', 'owner_id']);
        });
    }
};
