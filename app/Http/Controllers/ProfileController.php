<?php

namespace App\Http\Controllers;

use App\DTOs\UpdateProfileData;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function show(): View
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = UpdateProfileData::fromRequest($request);

        if ($data->password !== null) {
            if (!$user->password) {
                return back()->withErrors(['current_password' => 'This account uses Google Sign-In and has no password set.']);
            }
            if ($data->currentPassword === null || !Hash::check($data->currentPassword, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $this->profileService->updateProfile($user, $data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
