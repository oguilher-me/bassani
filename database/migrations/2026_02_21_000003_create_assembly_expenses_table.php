<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assembly_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_schedule_id')
                  ->constrained('assembly_schedules')
                  ->cascadeOnDelete();
            $table->foreignId('assembler_id')
                  ->constrained('assemblers')
                  ->cascadeOnDelete();
            $table->enum('category', [
                'Alimentação',
                'Hospedagem',
                'Combustível',
                'Pedágio',
                'Estacionamento',
                'Material Extra',
                'Outros',
            ]);
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('receipt_path')->nullable()->comment('Foto do comprovante fiscal');
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->text('rejection_reason')->nullable()->comment('Motivo da rejeição pelo financeiro');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assembly_expenses');
    }
};
