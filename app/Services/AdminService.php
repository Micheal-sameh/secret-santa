<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;
use App\Repositories\AdminRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    public function __construct(
        private AdminRepository $adminRepository
    ) {}

    public function getDashboardStats(): array
    {
        return [
            'total_users'          => $this->adminRepository->getTotalUsers(),
            'total_online_games'   => $this->adminRepository->getTotalOnlineGames(),
            'total_inperson_games' => $this->adminRepository->getTotalInPersonGames(),
            'total_assignments'    => $this->adminRepository->getTotalAssignments(),
            'recent_users'         => $this->adminRepository->getRecentUsers(5),
            'recent_games'         => $this->adminRepository->getRecentOnlineGames(5),
            'games_per_month'      => $this->adminRepository->getGamesPerMonth(),
        ];
    }

    public function getAllUsers(int $perPage = 20): LengthAwarePaginator
    {
        return $this->adminRepository->getAllUsersPaginated($perPage);
    }

    public function getAllGames(int $perPage = 20): LengthAwarePaginator
    {
        return $this->adminRepository->getAllGamesPaginated($perPage);
    }

    public function toggleAdmin(int $userId): User
    {
        $user = $this->adminRepository->findUser($userId);
        $this->adminRepository->toggleAdmin($user);
        return $user->fresh();
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->adminRepository->findUser($userId);
        $this->adminRepository->deleteUser($user);
    }

    public function deleteGame(int $gameId): void
    {
        $game = $this->adminRepository->findGame($gameId);
        $this->adminRepository->deleteGame($game);
    }
}