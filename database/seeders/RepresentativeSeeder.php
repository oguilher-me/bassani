<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representative;

class RepresentativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Representative::factory()->count(5)->create();
    }
}