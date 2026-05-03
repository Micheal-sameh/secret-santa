<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\InPersonGameController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // ── Games ─────────────────────────────────────────────────────────────────
    Route::get('/games',                  [GameController::class, 'index']);
    Route::post('/games',                 [GameController::class, 'store']);
    Route::get('/games/{token}',          [GameController::class, 'show']);
    Route::post('/games/{token}/join',    [GameController::class, 'join']);
    Route::post('/games/{id}/assign',     [GameController::class, 'assign']);
    Route::get('/games/{id}/my-assignment', [GameController::class, 'myAssignment']);
});

// ── In-Person (no auth) ───────────────────────────────────────────────────────
Route::post('/inperson',                                  [InPersonGameController::class, 'store']);
Route::get('/inperson/{token}',                           [InPersonGameController::class, 'show']);
Route::get('/inperson/{token}/participants',              [InPersonGameController::class, 'participants']);
Route::post('/inperson/{token}/reveal/{participantId}',   [InPersonGameController::class, 'reveal']);

