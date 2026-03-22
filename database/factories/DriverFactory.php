<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DriverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // password
            'role_id' => 2, // Assuming role_id 2 is for drivers
            'status' => true,
        ]);

        return [
            'user_id' => $user->id,
            'full_name' => $user->name,
            'cpf' => $this->faker->unique()->numerify('###########'),
            'cnh_number' => $this->faker->unique()->numerify('###########'),
            'cnh_category' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'AB', 'AC', 'AD', 'AE']),
            'cnh_expiration_date' => $this->faker->dateTimeBetween('+1 month', '+5 years')->format('Y-m-d'),
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['Ativo', 'Inativo', 'Suspenso']),
        ];
    }
}
