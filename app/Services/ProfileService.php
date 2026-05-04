<?php

namespace App\Services;

use App\DTOs\UpdateProfileData;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function updateProfile(User $user, UpdateProfileData $data): User
    {
        $updateData = [
            'name'  => $data->name,
            'email' => $data->email,
        ];

        if ($data->password !== null) {
            $updateData['password'] = Hash::make($data->password);
        }

        $this->userRepository->update($user, $updateData);

        return $user->fresh();
    }
}
