<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->firstName().' the '.$this->faker->randomElement(['Brave', 'Cozy', 'Swift']),
            'species' => $this->faker->randomElement(Pet::SPECIES),
            'level' => 1,
            'xp' => 0,
            'hunger' => 60,
            'hygiene' => 60,
            'happiness' => 60,
        ];
    }
}
