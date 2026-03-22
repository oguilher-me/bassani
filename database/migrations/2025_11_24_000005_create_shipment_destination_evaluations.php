<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipment_destination_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->string('token')->unique();
            $table->unsignedTinyInteger('nps_score')->nullable();
            $table->text('comments')->nullable();
            $table->json('photo_paths')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('destination_id')->references('id')->on('shipment_destinations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_destination_evaluations');
    }
};

