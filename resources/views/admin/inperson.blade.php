@extends('admin.layout')
@section('title', 'In-Person Games')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">All In-Person Games</h2>
        <p class="text-slate-400 text-sm">{{ $games->total() }} total games</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.inperson') }}" class="flex flex-wrap gap-3 mb-5">
    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
           placeholder="Search by name…"
           class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500 w-48">
    <select name="status"
            class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
        <option value="">All Statuses</option>
        <option value="open"  {{ ($filters['status'] ?? '') === 'open'  ? 'selected' : '' }}>Open</option>
        <option value="drawn" {{ ($filters['status'] ?? '') === 'drawn' ? 'selected' : '' }}>Drawn</option>
    </select>
    <button type="submit"
            class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 text-sm font-semibold transition-colors">
        Filter
    </button>
    @if(array_filter($filters))
    <a href="{{ route('admin.inperson') }}"
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
                    <th class="px-5 py-3 font-medium">Participants</th>
                    <th class="px-5 py-3 font-medium">Budget</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Created</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($games as $game)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('inperson.show', $game->device_token) }}" target="_blank"
                           class="text-white font-medium hover:text-amber-400 transition-colors flex items-center gap-1.5">
                            🎁 {{ $game->name }}
                            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-slate-300">{{ $game->participants_count }}</td>
                    <td class="px-5 py-3 text-slate-300">
                        {{ $game->price_limit ? '$'.number_format($game->price_limit, 0) : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        @if($game->assigned)
                            <span class="px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 text-xs font-semibold">Drawn</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 text-xs font-semibold">Open</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $game->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.inperson.destroy', $game->id) }}"
                              onsubmit="return confirm('Delete \'{{ addslashes($game->name) }}\'? This cannot be undone.')">
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
                    <td colspan="6" class="px-5 py-10 text-center text-slate-500">No in-person games found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($games->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $games->links() }}
    </div>
    @endif
</div>

@endsection
