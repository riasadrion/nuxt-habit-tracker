<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySummary extends Model
{
    /** @use HasFactory<\Database\Factories\DailySummaryFactory> */
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'date',
        'habits_completed',
        'mood_avg',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
