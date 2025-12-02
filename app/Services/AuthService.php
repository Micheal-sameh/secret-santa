<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        return Auth::attempt($credentials);
    }

    public function logout()
    {
        Auth::logout();
    }
}
