<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pets = $request->user()
            ->pets()
            ->latest()
            ->with(['actions' => fn ($query) => $query->latest()->limit(5)])
            ->get();

        return response()->json([
            'pets' => PetResource::collection($pets),
        ]);
    }

    public function store(StorePetRequest $request): JsonResponse
    {
        $pet = Pet::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ])->load(['actions' => fn ($query) => $query->latest()->limit(5)]);

        return (new PetResource($pet))->response()->setStatusCode(201);
    }

    public function show(Pet $pet): PetResource
    {
        $this->authorize('view', $pet);

        return new PetResource($pet->load(['actions' => fn ($query) => $query->latest()->limit(5)]));
    }

    public function update(UpdatePetRequest $request, Pet $pet): PetResource
    {
        $this->authorize('update', $pet);

        $pet->update($request->validated());

        return new PetResource($pet->refresh()->load(['actions' => fn ($query) => $query->latest()->limit(5)]));
    }

    public function destroy(Pet $pet): JsonResponse
    {
        $this->authorize('delete', $pet);

        $pet->delete();

        return response()->json([], 204);
    }
}
