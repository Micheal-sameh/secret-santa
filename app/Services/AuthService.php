<?php

namespace App\Services;

use App\DTOs\RegisterData;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function register(RegisterData $data): User
    {
        return $this->userRepository->create([
            'name'     => $data->name,
            'email'    => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials.');
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception('Unable to send reset link.');
        }

        return $status;
    }

    public function resetPassword(array $data): string
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $status;
    }

    public function findOrCreateGoogleUser(array $googleUser): User
    {
        $user = $this->userRepository->findByEmail($googleUser['email']);

        if (!$user) {
            $user = $this->userRepository->create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
                'avatar' => $googleUser['avatar'],
                'password' => Hash::make(uniqid()), // Random password for Google users
            ]);
        } else {
            // Update Google info if not set
            if (!$user->google_id) {
                $this->userRepository->update($user, [
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['avatar'],
                ]);
            }
        }

        return $user;
    }
}