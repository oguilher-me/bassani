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
        Schema::create('vehicle_fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->string('fine_number')->unique();
            $table->date('infraction_date');
            $table->date('notification_date')->nullable();
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('fine_type'); // e.g., "Speeding", "Parking", "Seatbelt", etc.
            $table->text('description');
            $table->string('location');
            $table->string('authority'); // e.g., “DETRAN-MT”, “PRF”, “Municipal Guard”
            $table->integer('points');
            $table->decimal('fine_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->enum('payment_status', ['Pending', 'Paid', 'Contested', 'Cancelled'])->default('Pending');
            $table->enum('responsible_for_payment', ['Company', 'Driver', 'Shared'])->default('Company');
            $table->string('document_reference')->nullable(); // e.g., boleto number or protocol
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fines');
    }
};
