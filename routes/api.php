<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::prefix('sessions')->group(function () {
        Route::get('/', [SessionController::class, 'index']);
        Route::post('/', [SessionController::class, 'store']);
        Route::delete('/{session}/participants/{participant}', [SessionController::class, 'destroyParticipant']);
        Route::post('/{session}/secret-santa', [SessionController::class, 'secretSanta']);
        Route::get('/{session}', [SessionController::class, 'show']);
    });
    Route::prefix('sessions')->group(function () {
        Route::post('/join', [SessionController::class, 'join']);
        Route::get('/{session}/assignment', [SessionController::class, 'showAssignment']);
    });
});
