<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\InPersonGameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Welcome ──────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('welcome');

// ── Auth (guest only) ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Password reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Game (public show, auth for actions) ─────────────────────────────────────
Route::get('/games/{token}', [GameController::class, 'show'])->name('games.show');
Route::get('/games/{token}/participants', [GameController::class, 'participants'])->name('games.participants');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [GameController::class, 'index'])->name('dashboard');
    Route::get('/games/create/new', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::post('/games/{token}/join', [GameController::class, 'join'])->name('games.join');
    Route::post('/games/{id}/assign', [GameController::class, 'assign'])->name('games.assign');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ── In-Person (no auth required) ─────────────────────────────────────────────
Route::prefix('inperson')->name('inperson.')->group(function () {
    Route::get('/create', [InPersonGameController::class, 'create'])->name('create');
    Route::post('/', [InPersonGameController::class, 'store'])->name('store');
    Route::get('/{token}', [InPersonGameController::class, 'show'])->name('show');
    Route::post('/{token}/reveal/{participantId}', [InPersonGameController::class, 'reveal'])->name('reveal');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::get('/inperson', [AdminController::class, 'inPersonGames'])->name('inperson');
    Route::get('/assignments', [AdminController::class, 'assignments'])->name('assignments');
    Route::post('/users/{userId}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/users/{userId}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::delete('/games/{gameId}', [AdminController::class, 'destroyGame'])->name('games.destroy');
    Route::delete('/inperson/{gameId}', [AdminController::class, 'destroyInPersonGame'])->name('inperson.destroy');
});

