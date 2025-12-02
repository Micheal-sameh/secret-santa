<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    private function generateToken($user)
    {
        return $user->createToken('API Token')->plainTextToken;
    }
}
