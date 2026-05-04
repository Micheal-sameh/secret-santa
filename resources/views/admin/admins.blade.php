@extends('admin.layout')
@section('title', 'Admins')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">Admin Users</h2>
        <p class="text-slate-400 text-sm">{{ $admins->total() }} admin{{ $admins->total() === 1 ? '' : 's' }}</p>
    </div>
</div>

<div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Hosted Games</th>
                    <th class="px-5 py-3 font-medium">Joined Games</th>
                    <th class="px-5 py-3 font-medium">Signed up</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($admins as $admin)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            @if($admin->avatar)
                                <img src="{{ $admin->avatar }}" alt="{{ $admin->name }}" class="w-7 h-7 rounded-full shrink-0">
                            @else
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-500 to-red-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="text-white font-medium truncate max-w-[140px] block">{{ $admin->name }}</span>
                                @if($admin->id === Auth::id())
                                    <span class="text-xs text-amber-400/70 italic">You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-slate-400 max-w-[180px] truncate">{{ $admin->email }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $admin->hosted_games_count }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $admin->participated_games_count }}</td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        @unless($admin->id === Auth::id())
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $admin->id) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-lg border border-amber-500/40 text-amber-400 hover:bg-amber-500/10 transition-colors">
                                    Revoke Admin
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $admin->id) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($admin->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-lg border border-red-500/40 text-red-400 hover:bg-red-500/10 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs text-slate-600 italic">Cannot modify yourself</span>
                        @endunless
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-slate-500">No admin users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $admins->links() }}
    </div>
    @endif
</div>

@endsection
