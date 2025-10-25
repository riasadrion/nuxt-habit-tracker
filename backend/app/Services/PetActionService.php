<?php

namespace App\Services;

use App\Events\PetActionPerformed;
use App\Events\PetLeveledUp;
use App\Models\Pet;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class PetActionService
{
    public const XP_PER_ACTION = 10;

    public const ACTION_DELTAS = [
        'feed' => ['hunger' => 25, 'hygiene' => -5, 'happiness' => 5],
        'clean' => ['hunger' => 0, 'hygiene' => 25, 'happiness' => 5],
        'play' => ['hunger' => -10, 'hygiene' => -5, 'happiness' => 25],
    ];

    public function perform(Pet $pet, string $actionType): Pet
    {
        if (! isset(self::ACTION_DELTAS[$actionType])) {
            abort(404, 'Unknown action');
        }

        $cooldownMs = $pet->cooldownRemaining($actionType);

        if ($cooldownMs > 0) {
            throw $this->cooldownException($cooldownMs);
        }

        return DB::transaction(function () use ($pet, $actionType): Pet {
            $pet->refresh();

            $cooldownMs = $pet->cooldownRemaining($actionType);
            if ($cooldownMs > 0) {
                throw $this->cooldownException($cooldownMs);
            }

            $config = self::ACTION_DELTAS[$actionType];
            $pet->hunger = $this->clamp($pet->hunger + $config['hunger']);
            $pet->hygiene = $this->clamp($pet->hygiene + $config['hygiene']);
            $pet->happiness = $this->clamp($pet->happiness + $config['happiness']);
            $pet->last_interaction_at = now();
            $pet->xp += self::XP_PER_ACTION;

            while ($pet->xp >= $this->xpThreshold($pet->level)) {
                $pet->xp -= $this->xpThreshold($pet->level);
                $pet->level++;
                event(new PetLeveledUp($pet));
            }

            $pet->save();

            $action = $pet->actions()->create([
                'type' => $actionType,
                'delta_hunger' => $config['hunger'],
                'delta_hygiene' => $config['hygiene'],
                'delta_happiness' => $config['happiness'],
                'xp_awarded' => self::XP_PER_ACTION,
                'metadata' => ['source' => 'api'],
            ]);

            event(new PetActionPerformed($pet->fresh(), $action));

            return $pet;
        });
    }

    protected function cooldownException(int $cooldownMs): HttpResponseException
    {
        return new HttpResponseException(response()->json([
            'message' => 'Action cooldown active',
            'cooldown_ms' => $cooldownMs,
        ], 429));
    }

    protected function xpThreshold(int $level): int
    {
        return 100 * max(1, $level);
    }

    protected function clamp(int $value): int
    {
        return (int) min(100, max(0, $value));
    }
}
