@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-300 transition-colors">Dashboard</a>
        <span>/</span>
        <span class="text-slate-300">My Profile</span>
    </div>

    <!-- Page header -->
    <div class="flex items-center gap-4 mb-8">
        @if($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                 class="w-16 h-16 rounded-full ring-2 ring-amber-500/40">
        @else
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-2xl font-black text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
            <p class="text-slate-400 text-sm mt-0.5">{{ $user->email }}</p>
            @if($user->google_id)
                <span class="inline-flex items-center gap-1 mt-1 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Connected via Google
                </span>
            @endif
        </div>
    </div>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/30 text-green-300 text-sm flex items-start gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Update form -->
    <div class="glass-card rounded-2xl p-6 sm:p-8">
        <h2 class="text-lg font-semibold text-white mb-6">Update Profile</h2>

        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                       placeholder="Your name">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                       placeholder="you@example.com">
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-700 pt-5">
                <h3 class="text-sm font-medium text-slate-400 mb-4">
                    Change Password
                    <span class="font-normal text-slate-500">(leave blank to keep current)</span>
                </h3>

                @if(!$user->google_id || $user->password)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                           placeholder="Your current password">
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">New Password</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                               placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                               placeholder="Repeat new password">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('dashboard') }}"
                   class="px-5 py-3 rounded-xl border border-slate-600 hover:border-slate-400 text-slate-300 hover:text-white text-sm font-medium transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-lg shadow-red-900/30">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
