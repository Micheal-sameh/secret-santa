<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Admin\AdminAssignmentRepository;
use App\Repositories\Admin\AdminGameRepository;
use App\Repositories\Admin\AdminInPersonGameRepository;
use App\Repositories\Admin\AdminUserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    public function __construct(
        private AdminUserRepository         $userRepo,
        private AdminGameRepository         $gameRepo,
        private AdminInPersonGameRepository $inPersonRepo,
        private AdminAssignmentRepository   $assignmentRepo,
    ) {}

    public function getDashboardStats(): array
    {
        $totalOnline   = $this->gameRepo->getTotalOnlineGames();
        $totalInPerson = $this->inPersonRepo->getTotalInPersonGames();

        return [
            'total_users'                => $this->userRepo->getTotalUsers(),
            'total_online_games'         => $totalOnline,
            'total_inperson_games'       => $totalInPerson,
            'total_games'                => $totalOnline + $totalInPerson,
            'total_assignments'          => $this->assignmentRepo->getTotalAssignments(),
            'total_online_assigned'      => $this->gameRepo->getTotalOnlineAssigned(),
            'total_inperson_assigned'    => $this->inPersonRepo->getTotalInPersonAssigned(),
            'total_online_participants'  => $this->gameRepo->getTotalOnlineParticipants(),
            'total_inperson_participants'=> $this->inPersonRepo->getTotalInPersonParticipants(),
            'new_users_this_month'       => $this->userRepo->getNewUsersThisMonth(),
            'new_games_this_month'       => $this->gameRepo->getNewOnlineGamesThisMonth(),
            'recent_users'               => $this->userRepo->getRecentUsers(5),
            'recent_games'               => $this->gameRepo->getRecentOnlineGames(5),
            'recent_inperson_games'      => $this->inPersonRepo->getRecentInPersonGames(5),
            'top_games'                  => $this->gameRepo->getTopOnlineGamesByParticipants(5),
            'games_per_month'            => $this->gameRepo->getGamesPerMonth(),
            'inperson_games_per_month'   => $this->inPersonRepo->getInPersonGamesPerMonth(),
        ];
    }

    public function getAllUsers(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->userRepo->getAllUsersPaginated($perPage, $filters);
    }

    public function getAllGames(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->gameRepo->getAllGamesPaginated($perPage, $filters);
    }

    public function getAllInPersonGames(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->inPersonRepo->getAllInPersonGamesPaginated($perPage, $filters);
    }

    public function getAllAssignments(int $perPage = 30, array $filters = []): LengthAwarePaginator
    {
        return $this->assignmentRepo->getAllAssignmentsPaginated($perPage, $filters);
    }

    public function getAllAdmins(int $perPage = 20): LengthAwarePaginator
    {
        return $this->userRepo->getAllAdminsPaginated($perPage);
    }

    public function toggleAdmin(int $userId): User
    {
        $user = $this->userRepo->findUser($userId);
        $this->userRepo->toggleAdmin($user);
        return $user->fresh();
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->userRepo->findUser($userId);
        $this->userRepo->deleteUser($user);
    }

    public function deleteGame(int $gameId): void
    {
        $game = $this->gameRepo->findGame($gameId);
        $this->gameRepo->deleteGame($game);
    }

    public function deleteInPersonGame(int $gameId): void
    {
        $game = $this->inPersonRepo->findInPersonGame($gameId);
        $this->inPersonRepo->deleteInPersonGame($game);
    }
}