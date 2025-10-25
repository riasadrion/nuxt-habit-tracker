<?php

namespace App\Console\Commands;

use App\Models\Pet;
use Illuminate\Console\Command;

class PetsDecay extends Command
{
    protected $signature = 'pets:decay';

    protected $description = 'Apply background stat decay to idle pets';

    public function handle(): int
    {
        $total = 0;

        Pet::chunkById(100, function ($pets) use (&$total) {
            foreach ($pets as $pet) {
                $hunger = max(0, $pet->hunger - 3);
                $hygiene = max(0, $pet->hygiene - 2);
                $happiness = max(0, $pet->happiness - 2);

                if ($hunger === $pet->hunger && $hygiene === $pet->hygiene && $happiness === $pet->happiness) {
                    continue;
                }

                $pet->update([
                    'hunger' => $hunger,
                    'hygiene' => $hygiene,
                    'happiness' => $happiness,
                ]);

                $total++;
            }
        });

        $this->info("Updated {$total} pets");

        return Command::SUCCESS;
    }
}
