<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->string('cpf_cnpj')->nullable()->after('user_id');
            $table->text('address')->nullable()->after('cpf_cnpj');
            $table->string('project_size')->nullable()->after('estimated_value'); // e.g., "150m2"
            $table->boolean('needs_project_development')->default(false)->after('project_size');
            $table->date('project_deadline')->nullable()->after('expected_closing_date');
        });
    }

    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'cpf_cnpj',
                'address',
                'project_size',
                'needs_project_development',
                'project_deadline'
            ]);
        });
    }
};
