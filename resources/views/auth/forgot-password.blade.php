@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h2 class="text-2xl font-bold text-white mb-2 text-center">Forgot password? 🔑</h2>
    <p class="text-slate-400 text-sm text-center mb-6">Enter your email and we'll send you a reset link.</p>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-500/10 border border-green-500/30 text-green-300 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                   placeholder="you@example.com">
        </div>
        <button type="submit"
                class="w-full py-3 px-6 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-lg shadow-red-900/30">
            Send Reset Link 📧
        </button>
    </form>
@endsection

@section('footer-link')
    Remember your password?
    <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-medium ml-1">Sign in</a>
@endsection
