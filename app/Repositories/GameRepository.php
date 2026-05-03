<?php

namespace App\Repositories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
{
    public function create(array $data): Game
    {
        return Game::create($data);
    }

    public function findByToken(string $token): ?Game
    {
        return Game::where('join_token', $token)->first();
    }

    public function findById(int $id): ?Game
    {
        return Game::find($id);
    }

    public function getCreatedGames(int $userId): Collection
    {
        return Game::where('host_id', $userId)
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();
    }

    public function getJoinedGames(int $userId): Collection
    {
        return Game::whereHas('participants', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
            ->where('host_id', '!=', $userId)
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();
    }

    public function getAssignedGames(int $userId): Collection
    {
        return Game::whereHas('assignments', function ($q) use ($userId) {
            $q->where('giver_id', $userId);
        })
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();
    }

    public function attachParticipant(Game $game, int $userId): void
    {
        $game->participants()->syncWithoutDetaching([$userId]);
    }

    public function update(Game $game, array $data): bool
    {
        return $game->update($data);
    }

    public function getParticipants(Game $game): Collection
    {
        return $game->participants;
    }

    public function getParticipantIds(Game $game): array
    {
        return $game->participants()->pluck('users.id')->toArray();
    }
}