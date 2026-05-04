@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="text-3xl mb-2">👥</div>
        <div class="text-3xl font-bold text-white">{{ number_format($total_users) }}</div>
        <div class="text-slate-400 text-sm mt-1">Total Users</div>
    </div>
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="text-3xl mb-2">🎮</div>
        <div class="text-3xl font-bold text-white">{{ number_format($total_online_games) }}</div>
        <div class="text-slate-400 text-sm mt-1">Online Games</div>
    </div>
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="text-3xl mb-2">🎁</div>
        <div class="text-3xl font-bold text-white">{{ number_format($total_inperson_games) }}</div>
        <div class="text-slate-400 text-sm mt-1">In-Person Games</div>
    </div>
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="text-3xl mb-2">🎯</div>
        <div class="text-3xl font-bold text-white">{{ number_format($total_assignments) }}</div>
        <div class="text-slate-400 text-sm mt-1">Assignments Made</div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">

    {{-- Recent Users --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-white">Recent Users</h2>
            <a href="{{ route('admin.users') }}" class="text-xs text-amber-400 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($recent_users as $user)
            <div class="flex items-center gap-3">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                @else
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-white font-medium truncate">
                        {{ $user->name }}
                        @if($user->is_admin)
                            <span class="ml-1 text-xs text-amber-400">Admin</span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
                </div>
                <div class="text-xs text-slate-500 shrink-0">{{ $user->created_at->diffForHumans() }}</div>
            </div>
            @empty
                <p class="text-slate-500 text-sm">No users yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Online Games --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-white">Recent Online Games</h2>
            <a href="{{ route('admin.games') }}" class="text-xs text-amber-400 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($recent_games as $game)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-slate-700 flex items-center justify-center text-sm">🎮</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-white font-medium truncate">{{ $game->name }}</div>
                    <div class="text-xs text-slate-500">
                        by {{ $game->host->name ?? 'Unknown' }} · {{ $game->participants_count }} players
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full shrink-0
                    {{ $game->assigned_at ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400' }}">
                    {{ $game->assigned_at ? 'Assigned' : 'Open' }}
                </span>
            </div>
            @empty
                <p class="text-slate-500 text-sm">No games yet.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Games per month table --}}
@if(count($games_per_month))
<div class="mt-6 bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
    <h2 class="font-semibold text-white mb-4">Games Created (Last 6 Months)</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700">
                    <th class="pb-2 font-medium">Month</th>
                    <th class="pb-2 font-medium">Year</th>
                    <th class="pb-2 font-medium">Games Created</th>
                    <th class="pb-2 font-medium">Bar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @php
                    $maxGames = collect($games_per_month)->max('total') ?: 1;
                    $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                @endphp
                @foreach($games_per_month as $row)
                <tr class="text-slate-300">
                    <td class="py-2.5">{{ $months[$row['month']] }}</td>
                    <td class="py-2.5">{{ $row['year'] }}</td>
                    <td class="py-2.5 font-semibold text-white">{{ $row['total'] }}</td>
                    <td class="py-2.5 w-48">
                        <div class="h-2 rounded-full bg-slate-700 overflow-hidden">
                            <div class="h-full rounded-full bg-amber-500"
                                 style="width: {{ round(($row['total'] / $maxGames) * 100) }}%"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection