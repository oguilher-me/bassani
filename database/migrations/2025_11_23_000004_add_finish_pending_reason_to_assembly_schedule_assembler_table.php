<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->text('finish_pending_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->dropColumn('finish_pending_reason');
        });
    }
};

