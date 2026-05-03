<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $game->name }} — Secret Santa 🎅</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(ellipse at top, #1a0a2e 0%, #0f172a 70%); min-height: 100vh; }
        .snowflake { position:fixed; top:-20px; pointer-events:none; z-index:0;
                    animation:snowfall linear infinite; color:rgba(255,255,255,0.35); }
        @keyframes snowfall {
            0%   { transform:translateY(-20px) rotate(0deg); opacity:0.7; }
            100% { transform:translateY(105vh) rotate(360deg); opacity:0; }
        }
        .glass-card { background:rgba(30,41,59,0.85); backdrop-filter:blur(12px);
                      border:1px solid rgba(51,65,85,0.5); }
        .name-card {
            background: linear-gradient(135deg, rgba(30,41,59,0.95) 0%, rgba(15,23,42,0.95) 100%);
            border: 1px solid rgba(245,158,11,0.3);
            box-shadow: 0 0 40px rgba(245,158,11,0.1);
        }
        @keyframes fadeInUp {
            0%   { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.5s ease forwards; }
        .avatar-ring { background: linear-gradient(135deg, #f59e0b, #dc2626); padding: 3px; border-radius: 9999px; }
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
</header>

@php
    $participants    = $game->participants->sortBy('reveal_order');
    $total           = $participants->count();
    $revealed        = $participants->where('revealed', true)->count();
    $nextParticipant = $participants->where('revealed', false)->first();
    $allDone         = $revealed === $total && $total > 0;
    $pct             = $total > 0 ? round($revealed / $total * 100) : 0;
@endphp

@if(session('success'))
    <div class="relative z-10 max-w-lg mx-auto px-4 mb-4">
        <div class="rounded-xl p-3 bg-green-500/10 border border-green-500/30 text-green-300 text-sm text-center">
            ✅ {{ session('success') }}
        </div>
    </div>
@endif

<main class="relative z-10 max-w-lg mx-auto px-4 pb-16">

    @if($allDone)
    <!-- All Done — celebration -->
    <div class="fade-in name-card rounded-3xl p-8 text-center mb-6">
        <div class="text-6xl mb-4 animate-bounce">🎉</div>
        <h2 class="text-2xl font-black text-white mb-2">Everyone's ready!</h2>
        <p class="text-slate-400 text-sm leading-relaxed mb-6">
            All {{ $total }} participants have seen their Secret Santa assignment.<br>
            Time to go shopping! 🛍️
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            @foreach($participants as $p)
            <div class="flex flex-col items-center gap-1">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center font-bold text-sm text-white ring-2 ring-green-400/30">
                    {{ strtoupper(substr($p->name, 0, 1)) }}
                </div>
                <span class="text-xs text-green-400">✓</span>
            </div>
            @endforeach
        </div>
    </div>

    @else
    <!-- Current person's turn -->
    <div class="fade-in name-card rounded-3xl p-8 text-center mb-6">

        <!-- Progress indicator -->
        <div class="mb-6">
            <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                <span>Progress</span>
                <span>{{ $revealed }} / {{ $total }} done</span>
            </div>
            <div class="h-1.5 rounded-full bg-slate-700 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-red-500 transition-all duration-500"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>

        <!-- Label -->
        <p class="text-slate-400 text-xs uppercase tracking-widest font-semibold mb-4">Next up</p>

        <!-- Avatar -->
        <div class="flex justify-center mb-4">
            <div class="avatar-ring">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center font-black text-3xl text-white">
                    {{ strtoupper(substr($nextParticipant->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Name -->
        <h2 class="text-3xl font-black text-white mb-1">{{ $nextParticipant->name }}</h2>
        <p class="text-slate-400 text-sm mb-8">
            Hand the device to <strong class="text-amber-400">{{ $nextParticipant->name }}</strong>, then tap below
        </p>

        <!-- Show button -->
        <form method="POST" action="{{ route('inperson.reveal', [$game->device_token, $nextParticipant->id]) }}">
            @csrf
            <button type="submit"
                    class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-bold text-lg transition-all shadow-xl shadow-red-900/40 active:scale-95"
                    style="touch-action:manipulation; -webkit-tap-highlight-color:transparent;">
                🎁 Show My Assignment
            </button>
        </form>

        <p class="text-slate-600 text-xs mt-4">Only tap after handing the device to {{ $nextParticipant->name }}</p>
    </div>

    <!-- Queue -->
    @if($total > 1)
    <div class="glass-card rounded-2xl p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Queue</p>
        <div class="space-y-2">
            @foreach($participants->sortBy('reveal_order') as $p)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 shrink-0 rounded-full flex items-center justify-center font-bold text-xs
                    @if($p->revealed) bg-green-500/20 text-green-400
                    @elseif($p->id === $nextParticipant->id) bg-amber-500/30 text-amber-300
                    @else bg-slate-700 text-slate-400 @endif">
                    {{ strtoupper(substr($p->name, 0, 1)) }}
                </div>
                <span class="text-sm @if($p->revealed) text-slate-500 line-through @elseif($p->id === $nextParticipant->id) text-amber-300 font-semibold @else text-slate-400 @endif">
                    {{ $p->name }}
                </span>
                @if($p->revealed)
                    <span class="ml-auto text-green-400 text-xs">✓ Done</span>
                @elseif($p->id === $nextParticipant->id)
                    <span class="ml-auto text-amber-400 text-xs animate-pulse">← Now</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('inperson.create') }}"
           class="text-sm text-slate-600 hover:text-slate-400 transition-colors">
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
