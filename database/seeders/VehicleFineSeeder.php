<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleFine;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleFineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        if ($vehicles->isEmpty() || $drivers->isEmpty()) {
            echo "Skipping VehicleFineSeeder: No vehicles or drivers found. Please run VehicleSeeder and DriverSeeder first.\n";
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $vehicle = $vehicles->random();
            $driver = $drivers->random();

            $infractionDate = Carbon::now()->subDays(rand(1, 365));
            $dueDate = $infractionDate->copy()->addDays(rand(15, 60));
            $paymentStatus = array_rand(PaymentStatus::cases());
            $responsibleForPayment = ['Company', 'Driver', 'Shared'][rand(0, 2)];

            VehicleFine::create([
                'vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'fine_number' => 'FINE-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'responsible_for_payment' => $responsibleForPayment,
                'infraction_date' => $infractionDate,
                'due_date' => $dueDate,
                'fine_amount' => rand(5000, 50000) / 100,
                'fine_type' => ['Excesso de Velocidade', 'Estacionamento Proibido', 'Avanco de Sinal', 'Uso de Celular'][rand(0, 3)],
                'payment_status' => PaymentStatus::cases()[$paymentStatus]->value,
                'description' => 'Multa de trânsito gerada automaticamente.',
                'location' => 'Rua Fictícia, 123 - Cidade Imaginária',
                'authority' => ['DETRAN-MT', 'PRF', 'Guarda Municipal'][rand(0, 2)],
                'points' => rand(3, 7),
            ]);
        }
    }
}
