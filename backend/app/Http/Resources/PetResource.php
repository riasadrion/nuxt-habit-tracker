<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'species' => $this->species,
            'level' => $this->level,
            'xp' => $this->xp,
            'hunger' => $this->hunger,
            'hygiene' => $this->hygiene,
            'happiness' => $this->happiness,
            'last_interaction_at' => $this->last_interaction_at,
            'cooldowns' => $this->cooldowns,
            'actions' => $this->whenLoaded('actions', function () {
                return $this->actions
                    ->sortByDesc('created_at')
                    ->take(5)
                    ->map(fn ($action) => [
                        'id' => $action->id,
                        'type' => $action->type,
                        'delta_hunger' => $action->delta_hunger,
                        'delta_hygiene' => $action->delta_hygiene,
                        'delta_happiness' => $action->delta_happiness,
                        'xp_awarded' => $action->xp_awarded,
                        'created_at' => $action->created_at,
                    ])->values();
            }),
        ];
    }
}
