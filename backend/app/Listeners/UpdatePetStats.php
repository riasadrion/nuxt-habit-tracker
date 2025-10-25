<?php

namespace App\Listeners;

use App\Events\PetActionPerformed;
use App\Models\DailySummary;

class UpdatePetStats
{
    public function handle(PetActionPerformed $event): void
    {
        $pet = $event->pet->fresh();

        $summary = DailySummary::query()->firstOrCreate(
            [
                'pet_id' => $pet->id,
                'date' => now()->toDateString(),
            ],
            [
                'habits_completed' => 0,
                'mood_avg' => 0,
            ]
        );

        $summary->habits_completed += 1;
        $summary->mood_avg = (int) round(($pet->hunger + $pet->hygiene + $pet->happiness) / 3);
        $summary->save();
    }
}
