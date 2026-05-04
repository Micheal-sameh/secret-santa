<?php

namespace App\Repositories\Admin;

use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminInPersonGameRepository
{
    public function getTotalInPersonGames(): int
    {
        return InPersonGame::count();
    }

    public function getTotalInPersonAssigned(): int
    {
        return InPersonGame::where('assigned', true)->count();
    }

    public function getTotalInPersonParticipants(): int
    {
        return InPersonParticipant::count();
    }

    public function getRecentInPersonGames(int $limit = 5): Collection
    {
        return InPersonGame::withCount('participants')->latest()->limit($limit)->get();
    }

    public function getInPersonGamesPerMonth(): array
    {
        return InPersonGame::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->limit(6)
            ->get()
            ->toArray();
    }

    public function getAllInPersonGamesPaginated(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = InPersonGame::withCount('participants')->latest();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
            $query->where('assigned', $filters['status'] === 'drawn');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findInPersonGame(int $id): ?InPersonGame
    {
        return InPersonGame::find($id);
    }

    public function deleteInPersonGame(InPersonGame $game): bool
    {
        return (bool) $game->delete();
    }
}
