<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_interactions', function (Blueprint $table) {
            // Rename content to notes if exists, or add notes
            if (Schema::hasColumn('crm_interactions', 'content')) {
                $table->renameColumn('content', 'notes');
            } else {
                $table->text('notes')->nullable();
            }
            
            // Add medium if not exists
            if (!Schema::hasColumn('crm_interactions', 'medium')) {
                $table->string('medium')->nullable()->after('type'); // email, whatsapp, system
            }

            // Add date if not exists
            if (!Schema::hasColumn('crm_interactions', 'date')) {
                $table->datetime('date')->nullable()->after('user_id');
            }

            // Update type enum if necessary
            $table->string('type')->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('crm_interactions', function (Blueprint $table) {
            if (Schema::hasColumn('crm_interactions', 'notes')) {
                $table->renameColumn('notes', 'content');
            }
            if (Schema::hasColumn('crm_interactions', 'medium')) {
                $table->dropColumn('medium');
            }
            // Reverting type to enum is tricky without exact definition
        });
    }
};
