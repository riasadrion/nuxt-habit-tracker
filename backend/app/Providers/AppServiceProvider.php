<?php

namespace App\Providers;

use App\Models\Pet;
use App\Policies\PetPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Pet::class, PetPolicy::class);

        RateLimiter::for('pet-actions', function (Request $request) {
            $key = optional($request->user())->id ?? $request->ip();

            return Limit::perMinute(15)->by($key);
        });

        RateLimiter::for('pet-create', function (Request $request) {
            $key = optional($request->user())->id ?? $request->ip();

            return Limit::perDay(10)->by($key);
        });
    }
}
