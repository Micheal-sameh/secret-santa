<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return to_route('sessions.index');
})->middleware('auth');

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {

    Route::prefix('sessions')->group(function () {
        Route::get('/', [SessionController::class, 'index'])->name('sessions.index');
        Route::get('/create', [SessionController::class, 'create'])->name('sessions.create');
        Route::post('/', [SessionController::class, 'store'])->name('sessions.store');
        Route::get('/{session}', [SessionController::class, 'show'])->name('sessions.show');
        Route::delete('/{session}/participants/{participant}', [SessionController::class, 'destroyParticipant'])->name('sessions.participants.destroy');
        Route::get('/{session}/secret-santa', [SessionController::class, 'secretSanta'])->name('sessions.secret-santa');
        Route::get('/{session}/secret-santa-pdf', [SessionController::class, 'exportSecretSantaPdf'])->name('sessions.secret-santa.export-pdf');
    });
});

Route::prefix('santa')->group(function () {
    Route::get('/join', [SessionController::class, 'joinForm'])->name('session.join-form');
    Route::post('/join', [SessionController::class, 'join'])->name('session.join');
    Route::get('/{session}/check-assignment', [SessionController::class, 'checkAssignment'])->name('sessions.check-assignment');
    Route::post('/{session}/check-assignment', [SessionController::class, 'showAssignment'])->name('sessions.show-assignment');
});

Route::prefix('paswword')->group(function () {
    Route::get('/forgot-password', [ForgetPasswordController::class, 'showForgetPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('password.update');
});
