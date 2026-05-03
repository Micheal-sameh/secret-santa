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
        <div class="flex flex-wrap gap-2 self-start sm:self-auto">
            <button onclick="openJoinByCode()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-medium text-sm transition-colors border border-slate-600">
                🔑 Join by Code
            </button>
            <button onclick="openQrScanner()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-medium text-sm transition-colors border border-slate-600">
                📷 Join by QR Code
            </button>
            <a href="{{ route('games.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow-lg shadow-red-900/30">
                ➕ Create New Game
            </a>
        </div>
    </div>

    <!-- Join by Code Modal -->
    <div id="join-code-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="glass-card rounded-2xl p-6 w-full max-w-sm" onclick="event.stopPropagation()">
            <h3 class="text-lg font-bold text-white mb-2">🔑 Join by Code</h3>
            <p class="text-slate-400 text-sm mb-4">Enter the game join code to go to that game:</p>
            <input type="text" id="join-code-input" placeholder="e.g. n0r4LzotlbA8"
                   class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all mb-4"
                   onkeydown="if(event.key==='Enter') submitJoinCode()">
            <div class="flex gap-2">
                <button onclick="closeJoinByCode()" class="flex-1 py-2.5 rounded-xl border border-slate-600 text-slate-300 hover:text-white text-sm font-medium transition-all">
                    Cancel
                </button>
                <button onclick="submitJoinCode()" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">
                    Go to Game
                </button>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div id="qr-scanner-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="glass-card rounded-2xl p-6 w-full max-w-sm" onclick="event.stopPropagation()">
            <h3 class="text-lg font-bold text-white mb-2">📷 Scan QR Code</h3>
            <p class="text-slate-400 text-sm mb-4">Point your camera at a Secret Santa game QR code:</p>
            <div id="qr-reader" class="rounded-xl overflow-hidden mb-4"></div>
            <div id="qr-status" class="text-sm text-slate-400 text-center mb-4 min-h-[20px]"></div>
            <button onclick="closeQrScanner()" class="w-full py-2.5 rounded-xl border border-slate-600 text-slate-300 hover:text-white text-sm font-medium transition-all">
                Cancel
            </button>
        </div>
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

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// ── Join by Code ──────────────────────────────────────────────────────────────
function openJoinByCode() {
    document.getElementById('join-code-modal').classList.remove('hidden');
    setTimeout(() => document.getElementById('join-code-input').focus(), 50);
}
function closeJoinByCode() {
    document.getElementById('join-code-modal').classList.add('hidden');
    document.getElementById('join-code-input').value = '';
}
function submitJoinCode() {
    const code = document.getElementById('join-code-input').value.trim();
    if (!code) return;
    window.location.href = '/games/' + encodeURIComponent(code);
}
document.getElementById('join-code-modal').addEventListener('click', closeJoinByCode);

// ── QR Scanner ────────────────────────────────────────────────────────────────
let html5QrCode = null;

function openQrScanner() {
    document.getElementById('qr-scanner-modal').classList.remove('hidden');
    document.getElementById('qr-status').textContent = 'Starting camera…';

    html5QrCode = new Html5Qrcode('qr-reader');
    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 220, height: 220 } },
        (decodedText) => {
            // Validate it looks like a game URL on this host
            try {
                const url = new URL(decodedText);
                const match = url.pathname.match(/^\/games\/([A-Za-z0-9]+)/);
                if (match) {
                    document.getElementById('qr-status').textContent = '✅ Code found! Redirecting…';
                    closeQrScanner();
                    window.location.href = decodedText;
                } else {
                    document.getElementById('qr-status').textContent = '⚠️ This QR code is not a Secret Santa game.';
                }
            } catch {
                document.getElementById('qr-status').textContent = '⚠️ Invalid QR code.';
            }
        },
        () => {}
    ).then(() => {
        document.getElementById('qr-status').textContent = 'Scanning… point at a game QR code';
    }).catch(err => {
        document.getElementById('qr-status').textContent = '❌ Camera access denied. ' + err;
    });
}

function closeQrScanner() {
    document.getElementById('qr-scanner-modal').classList.add('hidden');
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {}).finally(() => { html5QrCode = null; });
    }
}
document.getElementById('qr-scanner-modal').addEventListener('click', function(e) {
    if (e.target === this) closeQrScanner();
});
</script>
@endpush
