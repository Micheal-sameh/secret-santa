<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;

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
        $data = $request->validated();

        // Verify current password if user has a password and wants to change it
        if ($request->filled('password')) {
            if (!$user->password) {
                return back()->withErrors(['current_password' => 'This account uses Google Sign-In and has no password set.']);
            }
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $this->profileService->updateProfile($user, $data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
