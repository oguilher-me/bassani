<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->decimal('start_latitude', 10, 7)->nullable();
            $table->decimal('start_longitude', 10, 7)->nullable();
            $table->decimal('start_accuracy', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->dropColumn(['start_latitude', 'start_longitude', 'start_accuracy']);
        });
    }
};

