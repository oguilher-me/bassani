<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Capacidade cúbica disponível (m³) — usada para validar se o veículo
            // comporta a cubagem total dos itens de um pedido/projeto.
            $table->decimal('cubic_capacity', 10, 2)
                  ->nullable()
                  ->after('quilometragem_atual')
                  ->comment('Capacidade de carga cúbica do veículo em m³');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('cubic_capacity');
        });
    }
};
