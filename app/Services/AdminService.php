<?php

namespace App\Services;

use App\Models\Game;
use App\Models\InPersonGame;
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
        $totalOnline   = $this->adminRepository->getTotalOnlineGames();
        $totalInPerson = $this->adminRepository->getTotalInPersonGames();

        return [
            'total_users'                => $this->adminRepository->getTotalUsers(),
            'total_online_games'         => $totalOnline,
            'total_inperson_games'       => $totalInPerson,
            'total_games'                => $totalOnline + $totalInPerson,
            'total_assignments'          => $this->adminRepository->getTotalAssignments(),
            'total_online_assigned'      => $this->adminRepository->getTotalOnlineAssigned(),
            'total_inperson_assigned'    => $this->adminRepository->getTotalInPersonAssigned(),
            'total_online_participants'  => $this->adminRepository->getTotalOnlineParticipants(),
            'total_inperson_participants'=> $this->adminRepository->getTotalInPersonParticipants(),
            'new_users_this_month'       => $this->adminRepository->getNewUsersThisMonth(),
            'new_games_this_month'       => $this->adminRepository->getNewOnlineGamesThisMonth(),
            'recent_users'               => $this->adminRepository->getRecentUsers(5),
            'recent_games'               => $this->adminRepository->getRecentOnlineGames(5),
            'recent_inperson_games'      => $this->adminRepository->getRecentInPersonGames(5),
            'top_games'                  => $this->adminRepository->getTopOnlineGamesByParticipants(5),
            'games_per_month'            => $this->adminRepository->getGamesPerMonth(),
            'inperson_games_per_month'   => $this->adminRepository->getInPersonGamesPerMonth(),
        ];
    }

    public function getAllUsers(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->adminRepository->getAllUsersPaginated($perPage, $filters);
    }

    public function getAllGames(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->adminRepository->getAllGamesPaginated($perPage, $filters);
    }

    public function getAllInPersonGames(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->adminRepository->getAllInPersonGamesPaginated($perPage, $filters);
    }

    public function getAllAssignments(int $perPage = 30, array $filters = []): LengthAwarePaginator
    {
        return $this->adminRepository->getAllAssignmentsPaginated($perPage, $filters);
    }

    public function getAllAdmins(int $perPage = 20): LengthAwarePaginator
    {
        return $this->adminRepository->getAllAdminsPaginated($perPage);
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

    public function deleteInPersonGame(int $gameId): void
    {
        $game = $this->adminRepository->findInPersonGame($gameId);
        $this->adminRepository->deleteInPersonGame($game);
    }
}