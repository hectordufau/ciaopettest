<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('pt_BR');
        
        return [
            'name' => $faker->name(),
            'species' => $faker->randomElement(['Cachorro', 'Gato', 'Pássaro', 'Coelho']),
            'breed' => $faker->randomElement([null, $faker->word()]),
            'gender' => $faker->randomElement(['Macho', 'Fêmea', 'Desconhecido']),
            'birth_date' => $faker->date('Y-m-d', '-1 year'),
            'weight' => $faker->randomFloat(2, 0.5, 50),
            'microchip_number' => $faker->randomElement([null, $faker->numerify('##############')]),
            'notes' => $faker->randomElement([null, $faker->sentence()]),
            'user_id' => User::factory(),
        ];
    }
}
