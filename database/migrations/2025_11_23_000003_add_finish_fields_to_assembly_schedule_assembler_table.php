<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->timestamp('finished_at')->nullable();
            $table->text('finish_notes')->nullable();
            $table->text('finish_photo_paths')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assembly_schedule_assembler', function (Blueprint $table) {
            $table->dropColumn(['finished_at', 'finish_notes', 'finish_photo_paths']);
        });
    }
};

