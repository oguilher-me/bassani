<?php

namespace Database\Factories;

use App\Models\PaymentTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTermFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentTerm::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}