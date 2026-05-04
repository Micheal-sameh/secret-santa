<?php

namespace App\DTOs;

use App\Http\Requests\UpdateProfileRequest;

readonly class UpdateProfileData
{
    public function __construct(
        public string  $name,
        public string  $email,
        public ?string $currentPassword,
        public ?string $password,
    ) {}

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return new self(
            name:            $request->validated('name'),
            email:           $request->validated('email'),
            currentPassword: $request->filled('current_password')
                                 ? $request->validated('current_password')
                                 : null,
            password:        $request->filled('password')
                                 ? $request->validated('password')
                                 : null,
        );
    }
}
