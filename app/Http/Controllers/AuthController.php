<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showRegister()
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->only(['name', 'email', 'password']));

        return redirect('/')->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->login($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
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
                // Check if user exists with same email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => bcrypt(uniqid()), // Random password for Google users
                    ]);
                }
            }

            Auth::login($user);

            return redirect('/')->with('success', 'Login successful!');
        } catch (\Exception $e) {
            return redirect('/auth/login')->withErrors(['google' => 'Google authentication failed.']);
        }
    }
}
