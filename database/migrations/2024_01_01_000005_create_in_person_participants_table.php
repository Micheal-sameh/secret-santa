<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('in_person_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('in_person_games')->cascadeOnDelete();
            $table->string('name');
            $table->string('assigned_to')->nullable();
            $table->boolean('revealed')->default(false);
            $table->unsignedInteger('reveal_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_person_participants');
    }
};
