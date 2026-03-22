<?php
 
namespace Database\Seeders;

use App\Models\PlannedShipment;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class PlannedShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        if ($vehicles->isEmpty() || $drivers->isEmpty()) {
            $this->command->info('Skipping PlannedShipmentSeeder: No vehicles or drivers found. Please run VehicleSeeder and DriverSeeder first.');
            return;
        }

        PlannedShipment::factory(50)->make()->each(function ($plannedShipment) use ($vehicles, $drivers) {
            $plannedShipment->vehicle_id = $vehicles->random()->id;
            $plannedShipment->driver_id = $drivers->random()->id;
            $plannedShipment->save();
        });
    }
}