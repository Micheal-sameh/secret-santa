@extends('admin.layout')
@section('title', 'Plans')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">Subscription Plans</h2>
        <p class="text-slate-400 text-sm">{{ $plans->count() }} plan{{ $plans->count() === 1 ? '' : 's' }}</p>
    </div>
    <a href="{{ route('admin.plans.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 text-sm font-semibold transition-colors">
        + New Plan
    </a>
</div>

<div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Description</th>
                    <th class="px-5 py-3 font-medium">Price / year</th>
                    <th class="px-5 py-3 font-medium">Online limit</th>
                    <th class="px-5 py-3 font-medium">In-person limit</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($plans as $plan)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3 text-white font-medium">{{ $plan->name }}</td>
                    <td class="px-5 py-3 text-slate-400 max-w-xs truncate">{{ $plan->description ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-300">${{ number_format($plan->price, 2) }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $plan->online_game_participant_limit >= 9999 ? 'Unlimited' : $plan->online_game_participant_limit }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $plan->inperson_game_participant_limit >= 9999 ? 'Unlimited' : $plan->inperson_game_participant_limit }}</td>
                    <td class="px-5 py-3">
                        @if($plan->is_active)
                            <span class="px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 text-xs font-semibold">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-700 text-slate-500 text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                               class="text-xs px-2.5 py-1 rounded-lg border border-amber-500/40 text-amber-400 hover:bg-amber-500/10 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                                  onsubmit="return confirm('Delete plan \'{{ addslashes($plan->name) }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-lg border border-red-500/40 text-red-400 hover:bg-red-500/10 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-500">No plans yet. <a href="{{ route('admin.plans.create') }}" class="text-amber-400 hover:underline">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
