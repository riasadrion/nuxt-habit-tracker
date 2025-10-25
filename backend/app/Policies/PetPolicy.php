<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Pet $pet): bool
    {
        return $this->ownsPet($user, $pet);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Pet $pet): bool
    {
        return $this->ownsPet($user, $pet);
    }

    public function delete(User $user, Pet $pet): bool
    {
        return $this->ownsPet($user, $pet);
    }

    public function restore(User $user, Pet $pet): bool
    {
        return $this->ownsPet($user, $pet);
    }

    public function forceDelete(User $user, Pet $pet): bool
    {
        return false;
    }

    protected function ownsPet(User $user, Pet $pet): bool
    {
        return $pet->user_id === $user->id;
    }
}
