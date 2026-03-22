<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_opportunities', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (!Schema::hasColumn('crm_opportunities', 'customer_id')) {
                // Link to generic customers table or use crm_entities as customer wrapper?
                // The prompt says "customer_id (FK)". Assuming it links to 'customers' table 
                // but checking existing migrations, 'customers' table exists (Admin\Customer).
                $table->foreignId('customer_id')->nullable()->after('title')->constrained('customers')->nullOnDelete();
            }
            if (!Schema::hasColumn('crm_opportunities', 'partner_id')) {
                $table->foreignId('partner_id')->nullable()->after('customer_id')->constrained('crm_entities')->nullOnDelete();
            }
            if (!Schema::hasColumn('crm_opportunities', 'user_id')) {
                 $table->foreignId('user_id')->nullable()->after('partner_id')->constrained('users');
            }
            if (!Schema::hasColumn('crm_opportunities', 'video_call_link')) {
                $table->string('video_call_link')->nullable();
            }
            
            // Status update: The prompt asks for status (open, won, lost). 
            // Existing might be different. Let's ensure it covers these.
            // MariaDB enum change is hard. We can leave it or try to modify.
            // If it was just created in previous steps, we might be able to use it as is if it has specific values.
            // Previous migration showed: no status column on crm_opportunities, only stage_id.
            // Wait, previous file `create_furniture_crm_tables.php` showed:
            // $table->string('stage_id')->default('new');
            // No status column. So we add it.

            if (!Schema::hasColumn('crm_opportunities', 'status')) {
                $table->enum('status', ['open', 'won', 'lost'])->default('open')->after('stage_id');
            }
        });

        Schema::create('crm_opportunity_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('crm_opportunities')->cascadeOnDelete();
            $table->string('from_stage_id')->nullable();
            $table->string('to_stage_id');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('duration_in_days')->default(0); // Time spent in previous stage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_opportunity_history');
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'customer_id', 'partner_id', 'user_id', 'status', 'video_call_link']);
        });
    }
};
