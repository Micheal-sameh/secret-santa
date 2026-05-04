@extends('admin.layout')
@section('title', 'Settings')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-semibold text-white">App Settings</h2>
    <p class="text-slate-400 text-sm">Configure subscription limits and access rules.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 max-w-xl">
    @csrf

    @if($errors->any())
    <div class="px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Subscriptions --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5 space-y-5">
        <h3 class="text-white font-semibold text-sm border-b border-slate-700 pb-3">Subscriptions</h3>

        <div class="flex items-start gap-3">
            <input type="hidden" name="subscriptions_enabled" value="0">
            <input type="checkbox" name="subscriptions_enabled" id="subscriptions_enabled" value="1"
                   {{ ($settings['subscriptions_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                   class="mt-0.5 w-4 h-4 accent-amber-500">
            <div>
                <label for="subscriptions_enabled" class="text-slate-200 text-sm font-medium">Enable Subscriptions</label>
                <p class="text-slate-500 text-xs mt-0.5">When disabled, everyone can host games of any size for free.</p>
            </div>
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1.5">
                Online Game Free Participant Limit
            </label>
            <input type="number" name="online_game_free_limit"
                   value="{{ $settings['online_game_free_limit'] ?? 10 }}"
                   min="1"
                   class="w-40 bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
            <p class="text-slate-500 text-xs mt-1">Hosts can have up to this many participants without a subscription.</p>
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1.5">
                In-Person Game Free Participant Limit
            </label>
            <input type="number" name="inperson_game_free_limit"
                   value="{{ $settings['inperson_game_free_limit'] ?? 15 }}"
                   min="1"
                   class="w-40 bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
            <p class="text-slate-500 text-xs mt-1">In-person games can have up to this many participants without a subscription.</p>
        </div>
    </div>

    {{-- In-Person Login --}}
    <div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl p-5 space-y-4">
        <h3 class="text-white font-semibold text-sm border-b border-slate-700 pb-3">In-Person Games — Guest Access</h3>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1.5">
                Login Required After N Participants
            </label>
            <input type="number" name="inperson_login_required_after"
                   value="{{ $settings['inperson_login_required_after'] ?? 5 }}"
                   min="0"
                   class="w-40 bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
            <p class="text-slate-500 text-xs mt-1">
                Guest users can add up to this many names. Set to <strong class="text-slate-400">0</strong> to require login always.
            </p>
        </div>
    </div>

    <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold text-sm transition-colors">
        Save Settings
    </button>
</form>

@endsection
