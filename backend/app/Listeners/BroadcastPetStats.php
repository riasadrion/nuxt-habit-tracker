<?php

namespace App\Listeners;

use App\Events\PetActionPerformed;
use App\Events\PetLeveledUp;
use Illuminate\Support\Facades\Log;

class BroadcastPetStats
{
    public function handle(PetActionPerformed|PetLeveledUp $event): void
    {
        $pet = $event->pet;

        $payload = [
            'pet_id' => $pet->id,
            'level' => $pet->level,
        ];

        if ($event instanceof PetActionPerformed) {
            $payload['action'] = $event->action->type;
        } else {
            $payload['event'] = 'level_up';
        }

        Log::info('Pet stats updated', $payload);
    }
}
