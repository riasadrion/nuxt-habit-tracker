<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetActionFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(array_keys(Pet::ACTION_COOLDOWNS));
        $deltaMap = [
            'feed' => ['hunger' => 25, 'hygiene' => -5, 'happiness' => 5],
            'clean' => ['hunger' => 0, 'hygiene' => 25, 'happiness' => 5],
            'play' => ['hunger' => -10, 'hygiene' => -5, 'happiness' => 25],
        ];
        $deltas = $deltaMap[$type];

        return [
            'pet_id' => 1,
            'type' => $type,
            'delta_hunger' => $deltas['hunger'],
            'delta_hygiene' => $deltas['hygiene'],
            'delta_happiness' => $deltas['happiness'],
            'xp_awarded' => 10,
            'metadata' => ['source' => 'factory'],
        ];
    }
}
