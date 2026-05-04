<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\InPersonGame;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminRepository
{
    public function getTotalUsers(): int
    {
        return User::count();
    }

    public function getTotalOnlineGames(): int
    {
        return Game::count();
    }

    public function getTotalInPersonGames(): int
    {
        return InPersonGame::count();
    }

    public function getTotalAssignments(): int
    {
        return \App\Models\Assignment::count();
    }

    public function getRecentUsers(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return User::latest()->limit($limit)->get();
    }

    public function getRecentOnlineGames(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Game::with('host')->withCount('participants')->latest()->limit($limit)->get();
    }

    public function getGamesPerMonth(): array
    {
        return Game::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->limit(6)
            ->get()
            ->toArray();
    }

    public function getAllUsersPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return User::withCount(['hostedGames', 'participatedGames'])
            ->latest()
            ->paginate($perPage);
    }

    public function getAllGamesPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Game::with('host')
            ->withCount('participants')
            ->latest()
            ->paginate($perPage);
    }

    public function toggleAdmin(User $user): bool
    {
        $user->is_admin = !$user->is_admin;
        return $user->save();
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function deleteGame(Game $game): bool
    {
        return $game->delete();
    }

    public function findUser(int $id): ?User
    {
        return User::find($id);
    }

    public function findGame(int $id): ?Game
    {
        return Game::find($id);
    }
}