<?php

namespace App\Console\Commands;

use App\Models\DailySummary;
use App\Models\Pet;
use Illuminate\Console\Command;

class PetsDailySummary extends Command
{
    protected $signature = 'pets:daily-summary';

    protected $description = 'Snapshot each pet\'s habit progress for the day';

    public function handle(): int
    {
        $today = now();
        $start = $today->copy()->startOfDay();
        $end = $today->copy()->endOfDay();
        $count = 0;

        Pet::with(['actions' => fn ($query) => $query->whereBetween('created_at', [$start, $end])])
            ->chunkById(100, function ($pets) use (&$count, $today) {
                foreach ($pets as $pet) {
                    $completed = $pet->actions->count();

                    DailySummary::updateOrCreate(
                        [
                            'pet_id' => $pet->id,
                            'date' => $today->toDateString(),
                        ],
                        [
                            'habits_completed' => $completed,
                            'mood_avg' => (int) round(($pet->hunger + $pet->hygiene + $pet->happiness) / 3),
                        ]
                    );

                    $count++;
                }
            });

        $this->info("Summaries refreshed for {$count} pets");

        return Command::SUCCESS;
    }
}
