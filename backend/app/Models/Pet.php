<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    /** @use HasFactory<\Database\Factories\PetFactory> */
    use HasFactory;
    use SoftDeletes;

    public const SPECIES = ['cat', 'dog', 'fox', 'dragon', 'other'];

    public const ACTION_COOLDOWNS = [
        'feed' => 120,
        'clean' => 180,
        'play' => 240,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'level',
        'xp',
        'hunger',
        'hygiene',
        'happiness',
        'last_interaction_at',
    ];

    protected $casts = [
        'hunger' => 'integer',
        'hygiene' => 'integer',
        'happiness' => 'integer',
        'level' => 'integer',
        'xp' => 'integer',
        'last_interaction_at' => 'datetime',
    ];

    protected $appends = ['cooldowns'];

    /**
     * Cache the latest action timestamps.
     *
     * @var array<string, CarbonInterface|null>
     */
    protected array $cooldownCache = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(PetAction::class);
    }

    public function dailySummaries(): HasMany
    {
        return $this->hasMany(DailySummary::class);
    }

    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function getCooldownsAttribute(): array
    {
        return collect(self::ACTION_COOLDOWNS)
            ->mapWithKeys(fn (int $seconds, string $action) => [
                $action.'_ms' => $this->cooldownRemaining($action),
            ])->toArray();
    }

    public function cooldownRemaining(string $action): int
    {
        $cooldownSeconds = self::ACTION_COOLDOWNS[$action] ?? null;

        if (! $cooldownSeconds) {
            return 0;
        }

        $lastTimestamp = $this->latestActionTimestamp($action);

        if (! $lastTimestamp) {
            return 0;
        }

        $availableAt = $lastTimestamp->copy()->addSeconds($cooldownSeconds);
        $diffMs = now()->diffInMilliseconds($availableAt, false);

        return $diffMs > 0 ? $diffMs : 0;
    }

    protected function latestActionTimestamp(string $action): ?CarbonInterface
    {
        if (array_key_exists($action, $this->cooldownCache)) {
            return $this->cooldownCache[$action];
        }

        $lastAction = $this->actions()
            ->where('type', $action)
            ->latest()
            ->first();

        return $this->cooldownCache[$action] = $lastAction?->created_at;
    }
}
