<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrier;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrier::factory()->count(3)->create();
    }
}