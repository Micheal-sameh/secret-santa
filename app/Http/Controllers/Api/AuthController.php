<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->only(['name', 'email', 'password']));

        $token = $this->generateToken($user);

        return $this->apiResponse(new UserResource($user), 'User registered successfully',
            additional_data: ['token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->login($credentials)) {
            $user = Auth::user();
            $token = $this->generateToken($user);

            return $this->apiResponse(new UserResource($user), 'Login successful',
                additional_data: ['token' => $token]);
        }

        return $this->apiErrorResponse('Invalid credentials', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->apiResponse(null, 'Logged out successfully');
    }

    public function user(Request $request)
    {
        return $this->apiResponse(['user' => $request->user()], 'User data retrieved');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (! $user) {
                $user = User::where('email', $googleUser->email)->first();

                if (! $user) {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => bcrypt(uniqid()), // Random password for social login
                    ]);
                } else {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            $token = $this->generateToken($user);

            return $this->apiResponse(new UserResource($user), 'Login successful',
                additional_data: ['token' => $token]);
        } catch (\Exception $e) {
            return $this->apiErrorResponse('Google authentication failed', 401);
        }
    }

    private function generateToken($user)
    {
        return $user->createToken('API Token')->plainTextToken;
    }
}
