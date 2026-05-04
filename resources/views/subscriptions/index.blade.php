@extends('layouts.app')

@section('title', 'Subscribe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-white mb-2">Choose a Plan</h1>
        <p class="text-slate-400">Unlock higher participant limits with an annual subscription.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Current subscription --}}
    @if($activeSub)
    <div class="mb-8 p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300">
        <p class="font-semibold text-emerald-200 mb-1">✓ You have an active subscription</p>
        <p class="text-sm">
            <strong>{{ $activeSub->plan->name }}</strong>
            — expires {{ $activeSub->ends_at->format('M d, Y') }}
        </p>
        @if($activeSub->plan->online_game_participant_limit >= 9999)
            <p class="text-xs mt-1 text-emerald-400">Unlimited online participants · Unlimited in-person participants</p>
        @else
            <p class="text-xs mt-1 text-emerald-400">
                Up to {{ $activeSub->plan->online_game_participant_limit }} online participants ·
                Up to {{ $activeSub->plan->inperson_game_participant_limit }} in-person participants
            </p>
        @endif
    </div>
    @endif

    {{-- Plan cards --}}
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($plans as $plan)
        <div class="glass-card rounded-2xl p-6 flex flex-col">
            <div class="flex-1">
                <h2 class="text-xl font-bold text-white mb-1">{{ $plan->name }}</h2>
                @if($plan->description)
                    <p class="text-slate-400 text-sm mb-4">{{ $plan->description }}</p>
                @endif

                <div class="mb-5">
                    <span class="text-3xl font-bold text-amber-400">${{ number_format($plan->price, 2) }}</span>
                    <span class="text-slate-400 text-sm ml-1">/year</span>
                </div>

                <ul class="space-y-2 text-sm text-slate-300 mb-6">
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400">✓</span>
                        @if($plan->online_game_participant_limit >= 9999)
                            Unlimited online participants
                        @else
                            Up to {{ $plan->online_game_participant_limit }} online participants
                        @endif
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400">✓</span>
                        @if($plan->inperson_game_participant_limit >= 9999)
                            Unlimited in-person participants
                        @else
                            Up to {{ $plan->inperson_game_participant_limit }} in-person participants
                        @endif
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400">✓</span>
                        Annual billing
                    </li>
                </ul>
            </div>

            @if($activeSub && $activeSub->plan_id === $plan->id)
                <button disabled
                        class="w-full py-2.5 rounded-xl bg-emerald-600/40 text-emerald-300 font-semibold text-sm cursor-default">
                    Current Plan
                </button>
            @else
                <form method="POST" action="{{ route('subscribe.store') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold text-sm transition-colors">
                        Subscribe
                    </button>
                </form>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center text-slate-400 py-12">
            No plans available at the moment.
        </div>
        @endforelse
    </div>

</div>
@endsection
