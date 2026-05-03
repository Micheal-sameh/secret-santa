<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('in_person_games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_limit', 8, 2)->nullable();
            $table->string('device_token', 32)->unique();
            $table->boolean('assigned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_person_games');
    }
};
