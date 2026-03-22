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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('type'); // Preventiva, Corretiva, Revisão, Troca de Óleo, Pneus, Elétrica, Funilaria, etc.
            $table->date('maintenance_date');
            $table->integer('mileage');
            $table->decimal('cost', 10, 2);
            $table->text('description')->nullable();
            $table->string('supplier')->nullable();
            $table->string('status')->default('Agendada'); // Agendada, Em execução, Concluída, Cancelada
            $table->string('service_proof')->nullable(); // Caminho para o arquivo de comprovante
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
