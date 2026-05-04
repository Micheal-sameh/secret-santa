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
        $filters = $request->only(['search', 'role']);
        $users   = $this->adminService->getAllUsers(20, $filters);

        return view('admin.users', compact('users', 'filters'));
    }

    public function games(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $games   = $this->adminService->getAllGames(20, $filters);

        return view('admin.games', compact('games', 'filters'));
    }

    public function inPersonGames(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $games   = $this->adminService->getAllInPersonGames(20, $filters);

        return view('admin.inperson', compact('games', 'filters'));
    }

    public function assignments(Request $request): View
    {
        $filters     = $request->only(['search', 'game_id']);
        $assignments = $this->adminService->getAllAssignments(30, $filters);

        return view('admin.assignments', compact('assignments', 'filters'));
    }

    public function admins(Request $request): View
    {
        $admins = $this->adminService->getAllAdmins(20);

        return view('admin.admins', compact('admins'));
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

    public function destroyInPersonGame(int $gameId): RedirectResponse
    {
        $this->adminService->deleteInPersonGame($gameId);

        return back()->with('success', 'In-person game deleted successfully.');
    }
}