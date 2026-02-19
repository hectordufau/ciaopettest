<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'species' => fake()->randomElement(['Cachorro', 'Gato', 'Pássaro', 'Coelho']),
            'breed' => fake()->optional()->word(),
            'gender' => fake()->randomElement(['Macho', 'Fêmea', 'Desconhecido']),
            'birth_date' => fake()->date('Y-m-d', '-1 year'),
            'weight' => fake()->randomFloat(2, 0.5, 50),
            'microchip_number' => fake()->optional()->unique()->numerify('##############'),
            'notes' => fake()->optional()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
