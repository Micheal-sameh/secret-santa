<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    public function dashboard(): View
    {
        $stats = $this->adminService->getDashboardStats();

        return view('admin.dashboard', $stats);
    }

    public function users(Request $request): View
    {
        $users = $this->adminService->getAllUsers(20);

        return view('admin.users', compact('users'));
    }

    public function games(Request $request): View
    {
        $games = $this->adminService->getAllGames(20);

        return view('admin.games', compact('games'));
    }

    public function toggleAdmin(int $userId): RedirectResponse
    {
        $user = $this->adminService->toggleAdmin($userId);

        $msg = $user->is_admin ? "{$user->name} is now an admin." : "{$user->name} is no longer an admin.";

        return back()->with('success', $msg);
    }

    public function destroyUser(int $userId): RedirectResponse
    {
        $this->adminService->deleteUser($userId);

        return back()->with('success', 'User deleted successfully.');
    }

    public function destroyGame(int $gameId): RedirectResponse
    {
        $this->adminService->deleteGame($gameId);

        return back()->with('success', 'Game deleted successfully.');
    }
}