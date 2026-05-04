@extends('admin.layout')
@section('title', 'Users')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-white">All Users</h2>
        <p class="text-slate-400 text-sm">{{ $users->total() }} total users</p>
    </div>
</div>

<div class="bg-slate-800/70 border border-slate-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-400 text-left border-b border-slate-700 bg-slate-800/50">
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Hosted</th>
                    <th class="px-5 py-3 font-medium">Joined</th>
                    <th class="px-5 py-3 font-medium">Signed up</th>
                    <th class="px-5 py-3 font-medium">Role</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40">
                @forelse($users as $user)
                <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-7 h-7 rounded-full shrink-0">
                            @else
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-red-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-white font-medium truncate max-w-[140px]">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-slate-400 max-w-[180px] truncate">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $user->hosted_games_count }}</td>
                    <td class="px-5 py-3 text-slate-300">{{ $user->participated_games_count }}</td>
                    <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        @if($user->is_admin)
                            <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 text-xs font-semibold">Admin</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-700 text-slate-400 text-xs">User</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Toggle Admin --}}
                            @unless($user->id === Auth::id())
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user->id) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-lg border
                                               {{ $user->is_admin ? 'border-amber-500/40 text-amber-400 hover:bg-amber-500/10' : 'border-slate-600 text-slate-400 hover:bg-slate-700' }}
                                               transition-colors">
                                    {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-lg border border-red-500/40 text-red-400 hover:bg-red-500/10 transition-colors">
                                    Delete
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-slate-600 italic">You</span>
                            @endunless
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-slate-700/50">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection