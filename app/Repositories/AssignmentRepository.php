<?php

namespace App\Repositories;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository
{
    public function create(array $data): Assignment
    {
        return Assignment::create($data);
    }

    public function findByGameAndGiver(int $gameId, int $giverId): ?Assignment
    {
        return Assignment::where('game_id', $gameId)
            ->where('giver_id', $giverId)
            ->with('receiver')
            ->first();
    }

    public function getByGame(int $gameId): Collection
    {
        return Assignment::where('game_id', $gameId)->get();
    }

    public function existsForGame(int $gameId): bool
    {
        return Assignment::where('game_id', $gameId)->exists();
    }
}