<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentTerm;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentTerm::factory()->count(4)->create();
    }
}