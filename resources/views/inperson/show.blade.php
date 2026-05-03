<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $game->name }} — Secret Santa 🎅</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(ellipse at top, #1a0a2e 0%, #0f172a 70%); }
        .snowflake { position:fixed; top:-20px; pointer-events:none; z-index:0;
                    animation:snowfall linear infinite; color:rgba(255,255,255,0.35); }
        @keyframes snowfall {
            0%   { transform:translateY(-20px) rotate(0deg); opacity:0.7; }
            100% { transform:translateY(105vh) rotate(360deg); opacity:0; }
        }
        .glass-card { background:rgba(30,41,59,0.85); backdrop-filter:blur(12px);
                      border:1px solid rgba(51,65,85,0.5); }
        /* Participant tap button */
        .reveal-btn {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen text-white overflow-x-hidden">

<div id="snowflakes" aria-hidden="true"></div>

<!-- Header -->
<header class="relative z-10 pt-8 pb-4 px-4 text-center">
    <a href="{{ route('welcome') }}" class="text-2xl">🎅</a>
    <h1 class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $game->name }}</h1>
    @if($game->price_limit)
        <p class="text-amber-400 text-sm mt-1">🎁 Gift budget: ${{ number_format($game->price_limit, 2) }}</p>
    @endif
    <p class="text-slate-400 text-sm mt-2">
        {{ $game->participants->count() }} participants ·
        {{ $game->participants->where('revealed', true)->count() }} revealed
    </p>
</header>

@if(session('success'))
    <div class="relative z-10 max-w-lg mx-auto px-4 mb-4">
        <div class="rounded-xl p-3 bg-green-500/10 border border-green-500/30 text-green-300 text-sm text-center">
            ✅ {{ session('success') }}
        </div>
    </div>
@endif

<!-- Participants list -->
<main class="relative z-10 max-w-lg mx-auto px-4 pb-16">
    <div class="glass-card rounded-2xl overflow-hidden divide-y divide-slate-700/50">
        @foreach($game->participants->sortBy('reveal_order') as $participant)
        <div class="flex items-center justify-between px-5 py-4 gap-4">
            <!-- Name + status -->
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 shrink-0 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center font-bold text-sm text-white">
                    {{ strtoupper(substr($participant->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="font-medium text-white truncate">{{ $participant->name }}</div>
                    @if($participant->revealed)
                        <div class="text-xs text-green-400 mt-0.5">✓ Seen assignment</div>
                    @else
                        <div class="text-xs text-slate-500 mt-0.5">Hasn't looked yet</div>
                    @endif
                </div>
            </div>

            <!-- Action button -->
            <form method="POST" action="{{ route('inperson.reveal', [$game->device_token, $participant->id]) }}">
                @csrf
                <button type="submit"
                        class="reveal-btn shrink-0 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all
                               @if($participant->revealed)
                                   bg-slate-700/50 text-slate-400 hover:bg-slate-700 border border-slate-600
                               @else
                                   bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white shadow-lg shadow-red-900/30
                               @endif">
                    @if($participant->revealed)
                        👀 View Again
                    @else
                        🎁 Show Mine
                    @endif
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Progress -->
    @php
        $total    = $game->participants->count();
        $revealed = $game->participants->where('revealed', true)->count();
        $pct      = $total > 0 ? round($revealed / $total * 100) : 0;
    @endphp
    <div class="mt-6 glass-card rounded-2xl p-5">
        <div class="flex justify-between text-xs text-slate-400 mb-2">
            <span>Progress</span>
            <span>{{ $revealed }}/{{ $total }} revealed</span>
        </div>
        <div class="h-2 rounded-full bg-slate-700 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-red-500 to-amber-500 transition-all"
                 style="width: {{ $pct }}%"></div>
        </div>
        @if($revealed === $total && $total > 0)
            <p class="text-center text-green-400 text-sm mt-3 font-medium">🎉 Everyone has seen their assignment!</p>
        @endif
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('inperson.create') }}"
           class="text-sm text-slate-500 hover:text-slate-300 transition-colors">
            ← Start a new in-person game
        </a>
    </div>
</main>

<script>
    (function() {
        const c = document.getElementById('snowflakes');
        for (let i = 0; i < 15; i++) {
            const el = document.createElement('span');
            el.className = 'snowflake';
            el.textContent = ['❄','❅','❆'][Math.floor(Math.random()*3)];
            el.style.left = Math.random()*100+'vw';
            el.style.fontSize = (0.5+Math.random())+'rem';
            el.style.animationDuration = (10+Math.random()*15)+'s';
            el.style.animationDelay    = (Math.random()*10)+'s';
            c.appendChild(el);
        }
    })();
</script>
</body>
</html>
