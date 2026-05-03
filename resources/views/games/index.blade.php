@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-white">🎄 Your Games</h1>
            <p class="text-slate-400 mt-1">Track all your Secret Santa adventures</p>
        </div>
        <a href="{{ route('games.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow-lg shadow-red-900/30 self-start sm:self-auto">
            ➕ Create New Game
        </a>
    </div>

    {{-- ── Games I Created ──────────────────────────────────────────────────── --}}
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-5">
            <span class="text-2xl">🎮</span>
            <h2 class="text-xl font-bold text-white">Games I Created</h2>
            <span class="ml-2 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-500/20 text-red-400">
                {{ $createdGames->count() }}
            </span>
        </div>

        @if($createdGames->isEmpty())
            @include('partials.empty-state', ['message' => "You haven't created any games yet."])
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($createdGames as $game)
                    @include('partials.game-card', ['game' => $game, 'role' => 'host'])
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Games I Joined ───────────────────────────────────────────────────── --}}
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-5">
            <span class="text-2xl">🤝</span>
            <h2 class="text-xl font-bold text-white">Games I Joined</h2>
            <span class="ml-2 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-500/20 text-green-400">
                {{ $joinedGames->count() }}
            </span>
        </div>

        @if($joinedGames->isEmpty())
            @include('partials.empty-state', ['message' => "You haven't joined any games yet. Use a shared link to join!"])
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($joinedGames as $game)
                    @include('partials.game-card', ['game' => $game, 'role' => 'participant'])
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Games I'm Assigned To ────────────────────────────────────────────── --}}
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-5">
            <span class="text-2xl">🎁</span>
            <h2 class="text-xl font-bold text-white">My Gift Assignments</h2>
            <span class="ml-2 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-400">
                {{ $assignedGames->count() }}
            </span>
        </div>

        @if($assignedGames->isEmpty())
            @include('partials.empty-state', ['message' => "No assignments yet — check back after game hosts run the draw!"])
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($assignedGames as $game)
                    @include('partials.game-card', ['game' => $game, 'role' => 'assigned'])
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
