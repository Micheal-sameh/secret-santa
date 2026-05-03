@extends('layouts.app')

@section('title', 'In-Person Game')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

    <div class="glass-card rounded-2xl p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-3xl">🎁</span>
            <div>
                <h1 class="text-2xl font-bold text-white">In-Person Secret Santa</h1>
                <p class="text-slate-400 text-sm mt-0.5">No accounts needed — just names!</p>
            </div>
        </div>

        <div class="mt-4 mb-7 p-4 rounded-xl bg-amber-500/5 border border-amber-500/20 text-amber-200/80 text-sm leading-relaxed">
            💡 Enter everyone's names, submit, then pass the device around so each person taps their name privately to see who they're buying for.
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('inperson.store') }}" id="inperson-form" class="space-y-6">
            @csrf

            <!-- Game Name -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Game Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                       placeholder="e.g. Family Christmas Party">
            </div>

            <!-- Price Limit -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Gift Budget (optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                    <input type="number" name="price_limit" value="{{ old('price_limit') }}"
                           min="0" step="1"
                           class="w-full pl-7 pr-4 py-3 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all"
                           placeholder="30.00">
                </div>
            </div>

            <!-- Participants -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-medium text-slate-300">
                        Participants <span class="text-red-400">*</span>
                        <span class="ml-1 text-slate-500 font-normal">(min. 3)</span>
                    </label>
                    <span id="count-badge"
                          class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-700 text-slate-300">
                        0 people
                    </span>
                </div>

                <div id="participants-list" class="space-y-2 mb-3">
                    <!-- Participant rows injected by JS -->
                </div>

                <button type="button" onclick="addParticipant()"
                        class="w-full py-2.5 rounded-xl border-2 border-dashed border-slate-600 hover:border-amber-500/50 text-slate-400 hover:text-amber-400 text-sm font-medium transition-all flex items-center justify-center gap-2">
                    ➕ Add Person
                </button>
            </div>

            <button type="submit"
                    class="w-full py-4 px-6 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-base transition-colors shadow-lg shadow-amber-900/20">
                🎉 Create &amp; Assign Everyone
            </button>
        </form>
    </div>
</div>

<!-- Login Required Modal -->
<div id="login-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLoginModal()"></div>
    <div class="relative z-10 bg-slate-800 border border-slate-700 rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
        <div class="text-5xl mb-4">🔒</div>
        <h2 class="text-xl font-bold text-white mb-2">Login Required</h2>
        <p class="text-slate-400 text-sm mb-6">
            No login games are limited to <span class="text-amber-400 font-semibold">5 participants</span>.<br>
            Login to enjoy full features with unlimited participants!
        </p>
        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}"
               class="w-full py-3 px-6 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-sm transition-colors">
                Login to continue
            </a>
            <a href="{{ route('register') }}"
               class="w-full py-3 px-6 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-medium text-sm transition-colors">
                Create a free account
            </a>
            <button onclick="closeLoginModal()"
                    class="text-slate-500 hover:text-slate-300 text-sm transition-colors">
                Maybe later
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const MIN_PARTICIPANTS = 3;
const MAX_PARTICIPANTS_GUEST = 5;
const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
let count = 0;

function addParticipant(value = '', locked = false) {
    const currentRows = document.querySelectorAll('.participant-row').length;
    if (!locked && !IS_AUTHENTICATED && currentRows >= MAX_PARTICIPANTS_GUEST) {
        showLoginModal();
        return;
    }
    count++;
    const list = document.getElementById('participants-list');
    const div  = document.createElement('div');
    div.className = 'flex gap-2 items-center participant-row';
    div.dataset.id = count;

    const removeBtn = locked
        ? `<span class="w-9 h-9 shrink-0 flex items-center justify-center rounded-xl text-slate-600 cursor-not-allowed" title="Required participant">
               🔒
           </span>`
        : `<button type="button" onclick="removeParticipant(${count})"
                class="w-9 h-9 shrink-0 flex items-center justify-center rounded-xl hover:bg-red-500/20 text-slate-400 hover:text-red-400 transition-colors">
               ✕
           </button>`;

    div.innerHTML = `
        <input type="text" name="participants[]" value="${escapeHtml(value)}" required maxlength="100"
               placeholder="Name ${count}"
               class="flex-1 px-4 py-2.5 rounded-xl bg-slate-700/60 border border-slate-600 focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 text-white placeholder-slate-400 text-sm outline-none transition-all">
        ${removeBtn}
    `;
    list.appendChild(div);
    updateCount();
    if (!locked) div.querySelector('input').focus();
}

function removeParticipant(id) {
    const allRows = document.querySelectorAll('.participant-row');
    if (allRows.length <= MIN_PARTICIPANTS) {
        alert('A minimum of 3 participants is required.');
        return;
    }
    const row = document.querySelector(`[data-id="${id}"]`);
    if (row) row.remove();
    updateCount();
}

function updateCount() {
    const rows = document.querySelectorAll('.participant-row');
    const badge = document.getElementById('count-badge');
    badge.textContent = rows.length + (rows.length === 1 ? ' person' : ' people');
}

function showLoginModal() {
    const modal = document.getElementById('login-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLoginModal() {
    const modal = document.getElementById('login-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Pre-fill from old input if validation failed
@if(old('participants'))
    @php $oldParts = old('participants'); @endphp
    @foreach(old('participants') as $index => $name)
        addParticipant({{ json_encode($name) }}, {{ $index < 3 ? 'true' : 'false' }});
    @endforeach
    // Ensure minimum 3 locked rows exist even after validation
    while (count < MIN_PARTICIPANTS) addParticipant('', true);
@else
    // Start with 3 required (locked) rows
    addParticipant('', true); addParticipant('', true); addParticipant('', true);
@endif
</script>
@endpush
