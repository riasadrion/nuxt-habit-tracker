<?php

use App\Models\Pet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pet::class)->constrained()->cascadeOnDelete();
            $table->enum('type', array_keys(Pet::ACTION_COOLDOWNS));
            $table->smallInteger('delta_hunger');
            $table->smallInteger('delta_hygiene');
            $table->smallInteger('delta_happiness');
            $table->unsignedInteger('xp_awarded')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['pet_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_actions');
    }
};
