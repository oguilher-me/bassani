<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assembly_schedule_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_schedule_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique();
            $table->unsignedTinyInteger('nps_score')->nullable();
            $table->text('comments')->nullable();
            $table->text('photo_paths')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assembly_schedule_evaluations');
    }
};

