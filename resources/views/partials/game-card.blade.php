<div class="glass-card rounded-2xl p-5 flex flex-col gap-4 hover:border-slate-600/80 transition-all group">
    <!-- Header -->
    <div class="flex items-start justify-between gap-3">
        <h3 class="font-semibold text-white text-base leading-snug group-hover:text-amber-300 transition-colors">
            {{ $game->name }}
        </h3>
        @if($game->isAssigned())
            <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 border border-green-500/20">
                ✓ Assigned
            </span>
        @elseif($game->isExpired())
            <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-500/20 text-slate-400 border border-slate-500/20">
                Closed
            </span>
        @else
            <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/20">
                Open
            </span>
        @endif
    </div>

    <!-- Meta -->
    <div class="grid grid-cols-2 gap-2 text-xs text-slate-400">
        <div class="flex items-center gap-1.5">
            <span>📅</span>
            <span>Meet: <span class="text-slate-300">{{ $game->meeting_date->format('M d, Y') }}</span></span>
        </div>
        <div class="flex items-center gap-1.5">
            <span>⏳</span>
            <span>Join by: <span class="text-slate-300">{{ $game->end_date->format('M d, Y') }}</span></span>
        </div>
        @if($game->price_limit)
        <div class="flex items-center gap-1.5">
            <span>💰</span>
            <span>Budget: <span class="text-amber-400 font-medium">${{ number_format($game->price_limit, 2) }}</span></span>
        </div>
        @endif
        <div class="flex items-center gap-1.5">
            <span>👥</span>
            <span><span class="text-slate-300">{{ $game->participants_count }}</span> participants</span>
        </div>
    </div>

    <!-- Role badge -->
    @if(isset($role))
    <div class="text-xs text-slate-500">
        @if($role === 'host') 🎮 You are the host
        @elseif($role === 'participant') 🤝 You joined this game
        @elseif($role === 'assigned') 🎁 You have an assignment
        @endif
    </div>
    @endif

    <!-- Action -->
    <a href="{{ route('games.show', $game->join_token) }}"
       class="mt-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all
              @if($role === 'assigned') bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/20
              @elseif($role === 'host')  bg-red-500/20   hover:bg-red-500/30   text-red-300   border border-red-500/20
              @else                      bg-slate-700    hover:bg-slate-600    text-slate-200 border border-slate-600 @endif">
        @if($role === 'assigned') 🎁 View My Assignment
        @elseif($role === 'host')  🎮 Manage Game
        @else                      👀 View Game
        @endif
        <span>→</span>
    </a>
</div>
