<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Models\Game;
use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminRepository
{
    // ── Counts ────────────────────────────────────────────────────────────────

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
        return Assignment::count();
    }

    public function getTotalOnlineAssigned(): int
    {
        return Game::whereNotNull('assigned_at')->count();
    }

    public function getTotalInPersonAssigned(): int
    {
        return InPersonGame::where('assigned', true)->count();
    }

    public function getTotalOnlineParticipants(): int
    {
        return DB::table('game_participants')->count();
    }

    public function getTotalInPersonParticipants(): int
    {
        return InPersonParticipant::count();
    }

    public function getNewUsersThisMonth(): int
    {
        return User::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function getNewOnlineGamesThisMonth(): int
    {
        return Game::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    // ── Recent lists ──────────────────────────────────────────────────────────

    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::withCount(['hostedGames', 'participatedGames'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentOnlineGames(int $limit = 5): Collection
    {
        return Game::with('host')->withCount('participants')->latest()->limit($limit)->get();
    }

    public function getRecentInPersonGames(int $limit = 5): Collection
    {
        return InPersonGame::withCount('participants')->latest()->limit($limit)->get();
    }

    // ── Charts ────────────────────────────────────────────────────────────────

    public function getGamesPerMonth(): array
    {
        return Game::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->limit(6)
            ->get()
            ->toArray();
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

    public function getTopOnlineGamesByParticipants(int $limit = 5): Collection
    {
        return Game::with('host')
            ->withCount('participants')
            ->orderByDesc('participants_count')
            ->limit($limit)
            ->get();
    }

    // ── Paginated lists ───────────────────────────────────────────────────────

    public function getAllUsersPaginated(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = User::withCount(['hostedGames', 'participatedGames'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                                       ->orWhere('email', 'like', "%{$search}%"));
        }

        if (isset($filters['role'])) {
            $query->where('is_admin', $filters['role'] === 'admin');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getAllGamesPaginated(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = Game::with('host')->withCount('participants')->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
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

    public function getAllAssignmentsPaginated(int $perPage = 30, array $filters = []): LengthAwarePaginator
    {
        $query = Assignment::with(['game', 'giver', 'receiver'])->latest();

        if (!empty($filters['game_id'])) {
            $query->where('game_id', $filters['game_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('giver', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('receiver', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getAllAdminsPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return User::where('is_admin', true)
            ->withCount(['hostedGames', 'participatedGames'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // ── Mutations ─────────────────────────────────────────────────────────────

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

    public function deleteInPersonGame(InPersonGame $game): bool
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

    public function findInPersonGame(int $id): ?InPersonGame
    {
        return InPersonGame::find($id);
    }
}