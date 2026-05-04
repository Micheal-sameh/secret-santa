<?php

namespace App\Repositories\Admin;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminGameRepository
{
    public function getTotalOnlineGames(): int
    {
        return Game::count();
    }

    public function getTotalOnlineAssigned(): int
    {
        return Game::whereNotNull('assigned_at')->count();
    }

    public function getTotalOnlineParticipants(): int
    {
        return \Illuminate\Support\Facades\DB::table('game_participants')->count();
    }

    public function getNewOnlineGamesThisMonth(): int
    {
        return Game::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function getRecentOnlineGames(int $limit = 5): Collection
    {
        return Game::with('host')->withCount('participants')->latest()->limit($limit)->get();
    }

    public function getTopOnlineGamesByParticipants(int $limit = 5): Collection
    {
        return Game::with('host')
            ->withCount('participants')
            ->orderByDesc('participants_count')
            ->limit($limit)
            ->get();
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

    public function getAllGamesPaginated(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = Game::with('host')->withCount('participants')->latest();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
            match ($filters['status']) {
                'drawn'  => $query->whereNotNull('assigned_at'),
                'closed' => $query->whereNull('assigned_at')->where('end_date', '<', now()),
                'open'   => $query->whereNull('assigned_at')->where('end_date', '>=', now()),
                default  => null,
            };
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findGame(int $id): ?Game
    {
        return Game::find($id);
    }

    public function deleteGame(Game $game): bool
    {
        return (bool) $game->delete();
    }
}
