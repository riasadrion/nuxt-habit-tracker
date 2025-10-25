<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Services\PetActionService;
use Illuminate\Http\JsonResponse;

class PetActionController extends Controller
{
    public function __construct(private readonly PetActionService $actions)
    {
    }

    public function index(Pet $pet): JsonResponse
    {
        $this->authorize('view', $pet);

        $history = $pet->actions()->latest()->paginate(20);

        return response()->json($history);
    }

    public function feed(Pet $pet): PetResource
    {
        return $this->perform($pet, 'feed');
    }

    public function clean(Pet $pet): PetResource
    {
        return $this->perform($pet, 'clean');
    }

    public function play(Pet $pet): PetResource
    {
        return $this->perform($pet, 'play');
    }

    protected function perform(Pet $pet, string $action): PetResource
    {
        $this->authorize('update', $pet);

        $updated = $this->actions->perform($pet, $action)
            ->load(['actions' => fn ($query) => $query->latest()->limit(5)]);

        return new PetResource($updated);
    }
}
