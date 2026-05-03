@extends('layouts.app')

@section('title', $game->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <!-- Header row -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-300">Dashboard</a>
                    <span>/</span>
                @endauth
                <span class="text-slate-300">{{ $game->name }}</span>
            </div>
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                🎄 {{ $game->name }}
                @if($game->isAssigned())
                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-green-500/20 text-green-400 border border-green-500/20">Assigned</span>
                @elseif($game->isExpired())
                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-slate-500/20 text-slate-400 border border-slate-500/20">Closed</span>
                @else
                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/20">Open</span>
                @endif
            </h1>
            <p class="text-slate-400 mt-1">Hosted by {{ $game->host->name }}</p>
        </div>

        <!-- Host: Assign button -->
        @if($isHost && !$game->isAssigned())
            @if($game->isExpired())
                <form method="POST" action="{{ route('games.assign', $game->id) }}">
                    @csrf
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition-colors shadow-lg shadow-green-900/30 flex items-center gap-2">
                        🎯 Run Assignment Draw
                    </button>
                </form>
            @else
                <div class="text-xs text-slate-500 px-4 py-3 rounded-xl border border-slate-700 text-center">
                    ⏳ Assignment available<br>after {{ $game->end_date->format('M d') }}
                </div>
            @endif
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: game details + share link -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Game Details card -->
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Game Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-900/40 rounded-xl p-4">
                        <div class="text-xs text-slate-500 mb-1">Registration Deadline</div>
                        <div class="text-white font-medium">{{ $game->end_date->format('F d, Y') }}</div>
                        @if(!$game->isExpired())
                            <div class="text-xs text-amber-400 mt-1">
                                {{ $game->end_date->diffForHumans() }}
                            </div>
                        @else
                            <div class="text-xs text-slate-500 mt-1">Expired</div>
                        @endif
                    </div>
                    <div class="bg-slate-900/40 rounded-xl p-4">
                        <div class="text-xs text-slate-500 mb-1">Meeting Date</div>
                        <div class="text-white font-medium">{{ $game->meeting_date->format('F d, Y') }}</div>
                        <div class="text-xs text-blue-400 mt-1">{{ $game->meeting_date->diffForHumans() }}</div>
                    </div>
                    @if($game->price_limit)
                    <div class="bg-slate-900/40 rounded-xl p-4">
                        <div class="text-xs text-slate-500 mb-1">Gift Budget</div>
                        <div class="text-amber-400 font-semibold text-lg">${{ number_format($game->price_limit, 2) }}</div>
                    </div>
                    @endif
                    <div class="bg-slate-900/40 rounded-xl p-4">
                        <div class="text-xs text-slate-500 mb-1">Participants</div>
                        <div class="text-white font-semibold text-lg">{{ $game->participants->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Share Link + QR -->
            @if($isHost || $isParticipant)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">🔗 Share This Game</h2>
                <p class="text-sm text-slate-400 mb-3">Send this link or scan the QR code to join:</p>
                <div class="flex gap-2 mb-4">
                    <input type="text" id="share-link" readonly
                           value="{{ url('/games/' . $game->join_token) }}"
                           class="flex-1 px-4 py-2.5 rounded-xl bg-slate-900/60 border border-slate-600 text-slate-300 text-sm outline-none">
                    <button onclick="copyLink()"
                            class="px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium transition-colors flex items-center gap-1.5"
                            id="copy-btn">
                        📋 Copy
                    </button>
                </div>
                <!-- QR Code -->
                <div class="flex flex-col items-center gap-2">
                    <p class="text-xs text-slate-500">Scan to join:</p>
                    <div id="qrcode" class="p-3 bg-white rounded-xl inline-block"></div>
                </div>
            </div>
            @endif

            <!-- My Assignment reveal (if assigned) -->
            @if($myAssignment)
            <div class="glass-card rounded-2xl p-6 border-amber-500/30 relative overflow-hidden">
                <!-- Background glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-red-500/5 pointer-events-none"></div>
                <div class="relative z-10">
                    <h2 class="text-sm font-semibold text-amber-400 uppercase tracking-wider mb-4">🎁 Your Secret Santa Assignment</h2>
                    <div class="text-center py-6">
                        <p class="text-slate-400 text-sm mb-2">You're buying a gift for:</p>
                        <div class="text-4xl font-black text-white mb-2">{{ $myAssignment->receiver->name }}</div>
                        @if($game->price_limit)
                            <p class="text-amber-400 text-sm">💰 Budget: up to ${{ number_format($game->price_limit, 2) }}</p>
                        @endif
                        <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs">
                            🤫 Keep this a secret!
                        </div>
                    </div>
                </div>
            </div>
            @elseif($isParticipant && $game->isAssigned())
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="text-3xl mb-3">🎁</div>
                <p class="text-slate-400 text-sm">Assignments have been made! Check above to see who you're buying for.</p>
            </div>
            @elseif(!$isParticipant && $game->isAssigned())
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="text-3xl mb-3">🔒</div>
                <p class="text-slate-400 text-sm">Assignments have been made. You need to be a participant to view yours.</p>
            </div>
            @endif

        </div>

        <!-- Right: participants + join -->
        <div class="space-y-5">

            <!-- Join / Already joined -->
            @auth
                @if(!$isParticipant && !$game->isExpired() && !$game->isAssigned())
                <div class="glass-card rounded-2xl p-5">
                    <h2 class="font-semibold text-white mb-3">Ready to join?</h2>
                    <p class="text-slate-400 text-sm mb-4">Join this game to participate in the gift exchange!</p>
                    <form method="POST" action="{{ route('games.join', $game->join_token) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition-colors">
                            🎄 Join Game
                        </button>
                    </form>
                </div>
                @elseif($isParticipant && !$isHost)
                <div class="glass-card rounded-2xl p-5 text-center">
                    <div class="text-2xl mb-2">✅</div>
                    <p class="text-green-400 font-medium text-sm">You're in!</p>
                    <p class="text-slate-500 text-xs mt-1">Waiting for the draw…</p>
                </div>
                @elseif($game->isExpired() && !$isParticipant)
                <div class="glass-card rounded-2xl p-5 text-center">
                    <div class="text-2xl mb-2">🔒</div>
                    <p class="text-slate-400 text-sm">Registration closed</p>
                </div>
                @endif
            @else
                @if(!$game->isExpired() && !$game->isAssigned())
                <div class="glass-card rounded-2xl p-5">
                    <h2 class="font-semibold text-white mb-3">Want to join?</h2>
                    <p class="text-slate-400 text-sm mb-4">Log in or create an account to join this game.</p>
                    <a href="{{ route('login') }}"
                       class="block w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-center font-semibold text-sm transition-colors">
                        🎅 Login to Join
                    </a>
                </div>
                @endif
            @endauth

            <!-- Participants list -->
            <div class="glass-card rounded-2xl p-5" id="participants-card">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">
                    👥 Participants (<span id="participant-count">{{ $game->participants->count() }}</span>)
                </h2>
                <div id="participants-list">
                @if($game->participants->isEmpty())
                    <p class="text-slate-500 text-sm">No participants yet.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($game->participants as $participant)
                        <li class="flex items-center gap-3">
                            @if($participant->avatar)
                                <img src="{{ $participant->avatar }}" alt="{{ $participant->name }}"
                                     class="w-7 h-7 rounded-full ring-1 ring-slate-600">
                            @else
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(substr($participant->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm text-slate-300">{{ $participant->name }}</span>
                            @if($participant->id === $game->host_id)
                                <span class="text-xs text-amber-500">host</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
// QR Code generation
@if($isHost || $isParticipant)
(function() {
    const url = document.getElementById('share-link')?.value;
    if (url && typeof QRCode !== 'undefined') {
        new QRCode(document.getElementById('qrcode'), {
            text: url,
            width: 160,
            height: 160,
            colorDark: '#0f172a',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }
})();
@endif

// Copy link
function copyLink() {
    const input = document.getElementById('share-link');
    const btn   = document.getElementById('copy-btn');
    navigator.clipboard.writeText(input.value).then(() => {
        btn.textContent = '✅ Copied!';
        setTimeout(() => btn.textContent = '📋 Copy', 2000);
    }).catch(() => {
        input.select();
        document.execCommand('copy');
        btn.textContent = '✅ Copied!';
        setTimeout(() => btn.textContent = '📋 Copy', 2000);
    });
}

// Real-time participant polling (every 8 seconds, only if game is open)
@if(!$game->isAssigned() && !$game->isExpired())
(function() {
    const hostId = {{ $game->host_id }};

    function renderParticipants(data) {
        const countEl = document.getElementById('participant-count');
        const listEl  = document.getElementById('participants-list');
        if (!countEl || !listEl) return;

        countEl.textContent = data.count;

        if (data.count === 0) {
            listEl.innerHTML = '<p class="text-slate-500 text-sm">No participants yet.</p>';
            return;
        }

        const ul = document.createElement('ul');
        ul.className = 'space-y-2';

        data.participants.forEach(p => {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-3';
            const initial = p.name.charAt(0).toUpperCase();
            const avatar = p.avatar
                ? `<img src="${p.avatar}" alt="${p.name}" class="w-7 h-7 rounded-full ring-1 ring-slate-600">`
                : `<div class="w-7 h-7 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white">${initial}</div>`;
            const hostBadge = p.is_host ? '<span class="text-xs text-amber-500">host</span>' : '';
            li.innerHTML = `${avatar}<span class="text-sm text-slate-300">${p.name}</span>${hostBadge}`;
            ul.appendChild(li);
        });

        listEl.innerHTML = '';
        listEl.appendChild(ul);
    }

    function pollParticipants() {
        fetch('{{ route("games.participants", $game->join_token) }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => renderParticipants(data))
        .catch(() => {});
    }

    setInterval(pollParticipants, 8000);
})();
@endif
</script>
@endpush
