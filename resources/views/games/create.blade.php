@extends('layouts.app')

@section('title', 'Create Game')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-300 transition-colors">Dashboard</a>
        <span>/</span>
        <span class="text-slate-300">Create Game</span>
    </div>

    <div class="glass-card rounded-2xl p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-8">
            <span class="text-3xl">🎮</span>
            <div>
                <h1 class="text-2xl font-bold text-white">Create a Secret Santa Game</h1>
                <p class="text-slate-400 text-sm mt-0.5">You'll get a shareable link once created</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('games.store') }}" class="space-y-6">
            @csrf

            <!-- Game Name -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Game Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                       placeholder="e.g. Office Christmas 2025">
            </div>

            <!-- Price Limit -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Gift Budget (optional)
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                    <input type="number" name="price_limit" value="{{ old('price_limit') }}"
                           min="0" step="1"
                           class="w-full pl-7 pr-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                           placeholder="50.00">
                </div>
                <p class="text-xs text-slate-500 mt-1.5">Leave blank if there's no budget limit</p>
            </div>

            <!-- Dates row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Registration Deadline <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white text-sm outline-none transition-all [color-scheme:dark]">
                    <p class="text-xs text-slate-500 mt-1.5">Last day people can join</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Meeting Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="meeting_date" value="{{ old('meeting_date') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white text-sm outline-none transition-all [color-scheme:dark]">
                    <p class="text-xs text-slate-500 mt-1.5">When you'll exchange gifts</p>
                </div>
            </div>

            <!-- Info box -->
            <div class="rounded-xl bg-blue-500/5 border border-blue-500/20 p-4 text-sm text-blue-300 flex gap-3">
                <span class="shrink-0 mt-0.5">ℹ️</span>
                <span>After creating, you'll get a unique link to share. Once the deadline passes, return here to run the random assignment draw.</span>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('dashboard') }}"
                   class="px-5 py-3 rounded-xl border border-slate-600 hover:border-slate-400 text-slate-300 hover:text-white text-sm font-medium transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-lg shadow-red-900/30">
                    🎅 Create Game
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
