<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shipment_destinations', function (Blueprint $table) {
            $table->string('confirmation_status')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->string('start_photo_path')->nullable();
            $table->decimal('start_latitude', 10, 7)->nullable();
            $table->decimal('start_longitude', 10, 7)->nullable();
            $table->decimal('start_accuracy', 8, 2)->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->text('finish_notes')->nullable();
            $table->json('finish_photo_paths')->nullable();
            $table->text('finish_pending_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shipment_destinations', function (Blueprint $table) {
            $table->dropColumn([
                'confirmation_status','started_at','start_photo_path','start_latitude','start_longitude','start_accuracy','finished_at','finish_notes','finish_photo_paths','finish_pending_reason'
            ]);
        });
    }
};

