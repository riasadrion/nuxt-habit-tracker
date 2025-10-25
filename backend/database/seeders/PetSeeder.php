<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\PetAction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@pets.test'],
            [
                'name' => 'Demo Trainer',
                'password' => Hash::make('password'),
            ]
        );

        $pets = Pet::factory()
            ->count(2)
            ->sequence(
                ['name' => 'Ember', 'species' => 'dragon'],
                ['name' => 'Pixel', 'species' => 'fox']
            )
            ->create(['user_id' => $user->id]);

        foreach ($pets as $pet) {
            PetAction::factory()->count(5)->create(['pet_id' => $pet->id]);
        }
    }
}
