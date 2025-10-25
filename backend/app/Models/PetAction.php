<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetAction extends Model
{
    /** @use HasFactory<\Database\Factories\PetActionFactory> */
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'type',
        'delta_hunger',
        'delta_hygiene',
        'delta_happiness',
        'xp_awarded',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
