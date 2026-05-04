@extends('admin.layout')
@section('title', 'Assignments')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">All Assignments</h2>
        <p class="text-slate-400 text-sm">{{ $assignments->total() }} total assignments (online games)</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.assignments') }}" class="flex flex-wrap gap-3 mb-5">
    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
           placeholder="Search giver or receiver…"
           class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500 w-56">
    <input type="number" name="game_id" value="{{ $filters['game_id'] ?? '' }}"
           placeholder="Game ID…"
           class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500 w-32">
    <button type="submit"
            class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 text-sm font-semibold transition-colors">
        Filter
    </button>
    @if(array_filter($filters))
    <a href="{{ route('admin.assignments') }}"
       class="px-4 py-2 rounded-xl border border-slate-700 text-slate-400 hover:text-white text-sm transition-colors">
        Clear
    </a>
    @endif
</form>

<div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">Game</th>
                    <th class="px-5 py-3 font-medium">Giver</th>
                    <th class="px-5 py-3 font-medium">→</th>
                    <th class="px-5 py-3 font-medium">Receiver</th>
                    <th class="px-5 py-3 font-medium">Assigned</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($assignments as $assignment)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        @if($assignment->game)
                            <a href="{{ route('games.show', $assignment->game->join_token) }}" target="_blank"
                               class="text-amber-400 hover:underline font-medium flex items-center gap-1">
                                🎮 {{ $assignment->game->name }}
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @else
                            <span class="text-slate-500 italic">Deleted game</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($assignment->giver)
                            <div class="flex items-center gap-2">
                                @if($assignment->giver->avatar)
                                    <img src="{{ $assignment->giver->avatar }}" class="w-6 h-6 rounded-full shrink-0">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($assignment->giver->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-white">{{ $assignment->giver->name }}</span>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Deleted user</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500">🎁</td>
                    <td class="px-5 py-3">
                        @if($assignment->receiver)
                            <div class="flex items-center gap-2">
                                @if($assignment->receiver->avatar)
                                    <img src="{{ $assignment->receiver->avatar }}" class="w-6 h-6 rounded-full shrink-0">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($assignment->receiver->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-white">{{ $assignment->receiver->name }}</span>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Deleted user</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $assignment->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">No assignments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assignments->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $assignments->links() }}
    </div>
    @endif
</div>

@endsection

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">Game</th>
                    <th class="px-5 py-3 font-medium">Giver</th>
                    <th class="px-5 py-3 font-medium">→</th>
                    <th class="px-5 py-3 font-medium">Receiver</th>
                    <th class="px-5 py-3 font-medium">Assigned</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($assignments as $assignment)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        @if($assignment->game)
                            <a href="{{ route('games.show', $assignment->game->join_token) }}" target="_blank"
                               class="text-amber-400 hover:underline font-medium flex items-center gap-1">
                                🎮 {{ $assignment->game->name }}
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @else
                            <span class="text-slate-500 italic">Deleted game</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($assignment->giver)
                            <div class="flex items-center gap-2">
                                @if($assignment->giver->avatar)
                                    <img src="{{ $assignment->giver->avatar }}" class="w-6 h-6 rounded-full shrink-0">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($assignment->giver->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-white">{{ $assignment->giver->name }}</span>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Deleted user</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500">🎁</td>
                    <td class="px-5 py-3">
                        @if($assignment->receiver)
                            <div class="flex items-center gap-2">
                                @if($assignment->receiver->avatar)
                                    <img src="{{ $assignment->receiver->avatar }}" class="w-6 h-6 rounded-full shrink-0">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($assignment->receiver->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-white">{{ $assignment->receiver->name }}</span>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Deleted user</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $assignment->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">No assignments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assignments->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $assignments->links() }}
    </div>
    @endif
</div>

@endsection
