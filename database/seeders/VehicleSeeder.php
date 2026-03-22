<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create([
            'placa' => 'ABC1234',
            'modelo' => 'Gol',
            'car_brand_id' => 1, // Assumindo que a marca de carro com ID 1 existe
            'ano_fabricacao' => 2020,
            'quilometragem_atual' => 50000,
            'data_aquisicao' => '2020-01-15',
            'status' => 'ativo',
            'observacoes' => 'Veículo em bom estado.',
            'next_preventive_maintenance_mileage' => 60000,
            'licensing_due_date' => '2024-12-31',
            'insurance_due_date' => '2024-11-30',
        ]);

        Vehicle::create([
            'placa' => 'DEF5678',
            'modelo' => 'Palio',
            'car_brand_id' => 2, // Assumindo que a marca de carro com ID 2 existe
            'ano_fabricacao' => 2018,
            'quilometragem_atual' => 75000,
            'data_aquisicao' => '2018-03-20',
            'status' => 'ativo',
            'observacoes' => 'Necessita de troca de pneus.',
            'next_preventive_maintenance_mileage' => 80000,
            'licensing_due_date' => '2024-10-31',
            'insurance_due_date' => '2024-09-30',
        ]);
    }
}