@extends('admin.layout')
@section('title', 'Create Plan')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.plans.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1.5 mb-4">
        ← Back to Plans
    </a>
    <h2 class="text-lg font-semibold text-white">Create Plan</h2>
</div>

<div class="max-w-xl bg-slate-800/70 border border-slate-700/50 rounded-2xl p-6">
    <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-5">
        @csrf
        @include('admin.plans._form', ['plan' => null])
        <div class="pt-2">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold text-sm transition-colors">
                Create Plan
            </button>
        </div>
    </form>
</div>

@endsection
