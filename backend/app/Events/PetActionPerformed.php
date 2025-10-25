<?php

namespace App\Events;

use App\Models\Pet;
use App\Models\PetAction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PetActionPerformed
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Pet $pet,
        public PetAction $action,
    ) {
    }
}
