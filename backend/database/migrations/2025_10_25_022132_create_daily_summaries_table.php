<?php

use App\Models\Pet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pet::class)->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedTinyInteger('habits_completed')->default(0);
            $table->unsignedTinyInteger('mood_avg')->default(0);
            $table->timestamps();

            $table->unique(['pet_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_summaries');
    }
};
