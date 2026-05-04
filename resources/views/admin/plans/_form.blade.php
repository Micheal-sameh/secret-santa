{{-- Shared form fields for plan create/edit --}}

@if($errors->any())
<div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div>
    <label class="block text-slate-300 text-sm font-medium mb-1.5">Plan Name <span class="text-red-400">*</span></label>
    <input type="text" name="name" value="{{ old('name', $plan?->name) }}"
           placeholder="e.g. Pro, Business…"
           class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500">
</div>

<div>
    <label class="block text-slate-300 text-sm font-medium mb-1.5">Description</label>
    <textarea name="description" rows="3"
              placeholder="Short description of what this plan includes…"
              class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500 resize-none">{{ old('description', $plan?->description) }}</textarea>
</div>

<div>
    <label class="block text-slate-300 text-sm font-medium mb-1.5">Annual Price ($) <span class="text-red-400">*</span></label>
    <input type="number" name="price" value="{{ old('price', $plan?->price) }}" step="0.01" min="0"
           placeholder="9.99"
           class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-500">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1.5">Online Game Max Participants <span class="text-red-400">*</span></label>
        <input type="number" name="online_game_participant_limit"
               value="{{ old('online_game_participant_limit', $plan?->online_game_participant_limit ?? 9999) }}"
               min="1"
               class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
        <p class="text-slate-500 text-xs mt-1">Use 9999 for unlimited</p>
    </div>
    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1.5">In-Person Game Max Participants <span class="text-red-400">*</span></label>
        <input type="number" name="inperson_game_participant_limit"
               value="{{ old('inperson_game_participant_limit', $plan?->inperson_game_participant_limit ?? 9999) }}"
               min="1"
               class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
        <p class="text-slate-500 text-xs mt-1">Use 9999 for unlimited</p>
    </div>
</div>

<div class="flex items-center gap-3">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $plan?->is_active ?? true) ? 'checked' : '' }}
           class="w-4 h-4 accent-amber-500">
    <label for="is_active" class="text-slate-300 text-sm">Active (visible to users)</label>
</div>
