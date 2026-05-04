@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- ── Top stat cards ─────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

    {{-- Users --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4 col-span-1">
        <div class="text-2xl mb-1">👥</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_users) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">Total Users</div>
        @if($new_users_this_month > 0)
            <div class="text-xs text-green-400 mt-1">+{{ $new_users_this_month }} this month</div>
        @endif
    </div>

    {{-- Online Games --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4">
        <div class="text-2xl mb-1">🎮</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_online_games) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">Online Games</div>
        <div class="text-xs text-slate-500 mt-1">{{ $total_online_assigned }} assigned</div>
    </div>

    {{-- In-Person Games --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4">
        <div class="text-2xl mb-1">🎁</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_inperson_games) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">In-Person Games</div>
        <div class="text-xs text-slate-500 mt-1">{{ $total_inperson_assigned }} assigned</div>
    </div>

    {{-- Total Games --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4">
        <div class="text-2xl mb-1">🎉</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_games) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">All Games</div>
        @if($new_games_this_month > 0)
            <div class="text-xs text-green-400 mt-1">+{{ $new_games_this_month }} this month</div>
        @endif
    </div>

    {{-- Participants --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4">
        <div class="text-2xl mb-1">🙋</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_online_participants + $total_inperson_participants) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">Total Members</div>
        <div class="text-xs text-slate-500 mt-1">{{ $total_inperson_participants }} in-person</div>
    </div>

    {{-- Assignments --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-4">
        <div class="text-2xl mb-1">🎯</div>
        <div class="text-2xl font-bold text-white">{{ number_format($total_assignments) }}</div>
        <div class="text-slate-400 text-xs mt-0.5">Assignments</div>
        <div class="text-xs text-slate-500 mt-1">online draws</div>
    </div>

</div>

{{-- ── Assignment progress bars ────────────────────────────────────────────── --}}
<div class="grid lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white text-sm">Online Games Progress</h2>
            <span class="text-xs text-slate-500">{{ $total_online_assigned }} / {{ $total_online_games }} assigned</span>
        </div>
        @php $onlinePct = $total_online_games > 0 ? round(($total_online_assigned / $total_online_games) * 100) : 0; @endphp
        <div class="h-2.5 rounded-full bg-slate-700 overflow-hidden mb-1">
            <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-orange-400" style="width: {{ $onlinePct }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-500">
            <span>{{ $onlinePct }}% assigned</span>
            <span>{{ $total_online_games - $total_online_assigned }} open</span>
        </div>
    </div>
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white text-sm">In-Person Games Progress</h2>
            <span class="text-xs text-slate-500">{{ $total_inperson_assigned }} / {{ $total_inperson_games }} assigned</span>
        </div>
        @php $inpersonPct = $total_inperson_games > 0 ? round(($total_inperson_assigned / $total_inperson_games) * 100) : 0; @endphp
        <div class="h-2.5 rounded-full bg-slate-700 overflow-hidden mb-1">
            <div class="h-full rounded-full bg-gradient-to-r from-red-500 to-pink-400" style="width: {{ $inpersonPct }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-500">
            <span>{{ $inpersonPct }}% assigned</span>
            <span>{{ $total_inperson_games - $total_inperson_assigned }} open</span>
        </div>
    </div>
</div>

{{-- ── Four panels ─────────────────────────────────────────────────────────── --}}
<div class="grid lg:grid-cols-2 gap-6 mb-6">

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
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full shrink-0">
                @else
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-white font-medium truncate">
                        {{ $user->name }}
                        @if($user->is_admin)<span class="ml-1 text-xs text-amber-400">Admin</span>@endif
                    </div>
                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</div>
                    <div class="text-xs text-slate-600">{{ $user->hosted_games_count }} hosted</div>
                </div>
            </div>
            @empty
                <p class="text-slate-500 text-sm">No users yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Top Games by Players --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-white">Top Games by Players</h2>
            <a href="{{ route('admin.games') }}" class="text-xs text-amber-400 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($top_games as $i => $game)
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-lg bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-400 shrink-0">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-white font-medium truncate">{{ $game->name }}</div>
                    <div class="text-xs text-slate-500">by {{ $game->host->name ?? 'Unknown' }}</div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-sm font-semibold text-white">{{ $game->participants_count }}</div>
                    <div class="text-xs text-slate-500">players</div>
                </div>
            </div>
            @empty
                <p class="text-slate-500 text-sm">No games yet.</p>
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
                <div class="w-8 h-8 rounded-xl bg-slate-700 flex items-center justify-center text-sm shrink-0">🎮</div>
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

    {{-- Recent In-Person Games --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-white">Recent In-Person Games</h2>
            <a href="{{ route('admin.inperson') }}" class="text-xs text-amber-400 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($recent_inperson_games as $game)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-slate-700 flex items-center justify-center text-sm shrink-0">🎁</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-white font-medium truncate">{{ $game->name }}</div>
                    <div class="text-xs text-slate-500">{{ $game->participants_count }} participants</div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full shrink-0
                    {{ $game->assigned ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400' }}">
                    {{ $game->assigned ? 'Assigned' : 'Open' }}
                </span>
            </div>
            @empty
                <p class="text-slate-500 text-sm">No in-person games yet.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- ── Activity charts (side by side) ─────────────────────────────────────── --}}
<div class="grid lg:grid-cols-2 gap-6">

    @if(count($games_per_month))
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <h2 class="font-semibold text-white mb-4">Online Games — Last 6 Months</h2>
        @php
            $maxG = collect($games_per_month)->max('total') ?: 1;
            $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        @endphp
        <div class="space-y-2">
            @foreach($games_per_month as $row)
            <div class="flex items-center gap-3 text-sm">
                <span class="w-8 text-slate-500 text-xs shrink-0">{{ $months[$row['month']] }}</span>
                <div class="flex-1 h-2 rounded-full bg-slate-700 overflow-hidden">
                    <div class="h-full rounded-full bg-amber-500" style="width: {{ round(($row['total'] / $maxG) * 100) }}%"></div>
                </div>
                <span class="text-white font-semibold text-xs w-4 text-right shrink-0">{{ $row['total'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(count($inperson_games_per_month))
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5">
        <h2 class="font-semibold text-white mb-4">In-Person Games — Last 6 Months</h2>
        @php
            $maxI = collect($inperson_games_per_month)->max('total') ?: 1;
        @endphp
        <div class="space-y-2">
            @foreach($inperson_games_per_month as $row)
            <div class="flex items-center gap-3 text-sm">
                <span class="w-8 text-slate-500 text-xs shrink-0">{{ $months[$row['month']] }}</span>
                <div class="flex-1 h-2 rounded-full bg-slate-700 overflow-hidden">
                    <div class="h-full rounded-full bg-red-500" style="width: {{ round(($row['total'] / $maxI) * 100) }}%"></div>
                </div>
                <span class="text-white font-semibold text-xs w-4 text-right shrink-0">{{ $row['total'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection