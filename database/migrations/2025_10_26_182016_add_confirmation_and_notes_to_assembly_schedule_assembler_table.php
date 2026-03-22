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
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->string('confirmation_status')->default('pending'); // pending, confirmed, cancelled
            $table->text('assembler_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->dropColumn('confirmation_status');
            $table->dropColumn('assembler_notes');
        });
    }
};
