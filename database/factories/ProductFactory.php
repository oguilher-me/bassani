<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'base_price' => $this->faker->randomFloat(2, 10, 1000),
            'unit_of_measure' => $this->faker->randomElement(['kg', 'liter', 'unit']),
            'gross_weight' => $this->faker->randomFloat(2, 0.1, 100),
            'net_weight' => $this->faker->randomFloat(2, 0.1, 90),
            'cubic_volume' => $this->faker->randomFloat(4, 0.0001, 10),
            'sku' => $this->faker->unique()->ean13(),
        ];
    }
}