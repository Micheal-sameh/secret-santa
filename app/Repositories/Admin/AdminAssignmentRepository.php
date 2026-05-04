<?php

namespace App\Repositories\Admin;

use App\Models\Assignment;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminAssignmentRepository
{
    public function getTotalAssignments(): int
    {
        return Assignment::count();
    }

    public function getAllAssignmentsPaginated(int $perPage = 30, array $filters = []): LengthAwarePaginator
    {
        $query = Assignment::with(['game', 'giver', 'receiver'])->latest();

        if (!empty($filters['game_id'])) {
            $query->where('game_id', $filters['game_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn ($q) =>
                $q->whereHas('giver', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('receiver', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
            );
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
