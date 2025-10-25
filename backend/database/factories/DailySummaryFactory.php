<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DailySummaryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => 1,
            'date' => $this->faker->date(),
            'habits_completed' => $this->faker->numberBetween(0, 9),
            'mood_avg' => $this->faker->numberBetween(10, 90),
        ];
    }
}
