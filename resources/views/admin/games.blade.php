@extends('admin.layout')
@section('title', 'Online Games')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">All Online Games</h2>
        <p class="text-slate-400 text-sm">{{ $games->total() }} total games</p>
    </div>
</div>

<div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">Game</th>
                    <th class="px-5 py-3 font-medium">Host</th>
                    <th class="px-5 py-3 font-medium">Players</th>
                    <th class="px-5 py-3 font-medium">Budget</th>
                    <th class="px-5 py-3 font-medium">Deadline</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Created</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($games as $game)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('games.show', $game->join_token) }}" target="_blank"
                           class="text-white font-medium hover:text-amber-400 transition-colors flex items-center gap-1.5">
                            🎮 {{ $game->name }}
                            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-slate-300">{{ $game->host->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $game->participants_count }}</td>
                    <td class="px-5 py-3 text-slate-300">
                        {{ $game->price_limit ? '$'.number_format($game->price_limit, 0) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">
                        {{ $game->end_date ? \Carbon\Carbon::parse($game->end_date)->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        @if($game->assigned_at)
                            <span class="px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 text-xs font-semibold">Assigned</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 text-xs font-semibold">Open</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $game->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.games.destroy', $game->id) }}"
                              onsubmit="return confirm('Delete game \'{{ addslashes($game->name) }}\'? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-2.5 py-1 rounded-lg border border-red-500/40 text-red-400 hover:bg-red-500/10 transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-slate-500">No games found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($games->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $games->links() }}
    </div>
    @endif
</div>

@endsection