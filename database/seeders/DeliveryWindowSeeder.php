<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryWindow;

class DeliveryWindowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryWindow::create([
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'day_of_week' => 'Segunda-feira',
            'status' => 'Ativo',
        ]);

        DeliveryWindow::create([
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'day_of_week' => 'Terça-feira',
            'status' => 'Ativo',
        ]);

        DeliveryWindow::create([
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'day_of_week' => 'Quarta-feira',
            'status' => 'Inativo',
        ]);
    }
}
