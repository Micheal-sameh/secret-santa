@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h2 class="text-2xl font-bold text-white mb-6 text-center">Reset password 🔒</h2>

    @if($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                   placeholder="you@example.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">New Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                   placeholder="Min. 8 characters">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                   placeholder="Repeat password">
        </div>
        <button type="submit"
                class="w-full py-3 px-6 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-lg shadow-red-900/30">
            Reset Password 🎅
        </button>
    </form>
@endsection

@section('footer-link')
    Remembered it?
    <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-medium ml-1">Sign in</a>
@endsection
