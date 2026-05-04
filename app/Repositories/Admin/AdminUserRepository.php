<?php

namespace App\Repositories\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminUserRepository
{
    public function getTotalUsers(): int
    {
        return User::count();
    }

    public function getNewUsersThisMonth(): int
    {
        return User::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::withCount(['hostedGames', 'participatedGames'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getAllUsersPaginated(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = User::withCount(['hostedGames', 'participatedGames'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                                       ->orWhere('email', 'like', "%{$search}%"));
        }

        if (!empty($filters['role'])) {
            $query->where('is_admin', $filters['role'] === 'admin');
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

    public function findUser(int $id): ?User
    {
        return User::find($id);
    }

    public function toggleAdmin(User $user): bool
    {
        $user->is_admin = !$user->is_admin;
        return $user->save();
    }

    public function deleteUser(User $user): bool
    {
        return (bool) $user->delete();
    }
}
