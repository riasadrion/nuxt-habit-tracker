<?php

namespace App\Events;

use App\Models\Pet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PetLeveledUp
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Pet $pet)
    {
    }
}
