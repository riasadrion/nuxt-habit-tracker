<?php

namespace App\Providers;

use App\Events\PetActionPerformed;
use App\Events\PetLeveledUp;
use App\Listeners\BroadcastPetStats;
use App\Listeners\UpdatePetStats;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PetActionPerformed::class => [
            UpdatePetStats::class,
            BroadcastPetStats::class,
        ],
        PetLeveledUp::class => [
            BroadcastPetStats::class,
        ],
    ];
}
