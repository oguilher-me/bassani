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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_type'); // PF/PJ
            $table->string('full_name')->nullable(); // Nome completo (PF)
            $table->string('company_name')->nullable(); // Razão social (PJ)
            $table->string('cpf')->unique()->nullable(); // CPF (PF)
            $table->string('cnpj')->unique()->nullable(); // CNPJ (PJ)
            $table->string('rg')->nullable(); // RG (PF)
            $table->string('ie')->nullable(); // Inscrição Estadual (PJ)
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address_street');
            $table->string('address_number');
            $table->string('address_neighborhood');
            $table->string('address_city');
            $table->string('address_state');
            $table->string('address_zip_code');
            $table->string('address_type'); // residencial, comercial, entrega, cobrança
            $table->string('status')->default('Ativo'); // ativo/inativo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
