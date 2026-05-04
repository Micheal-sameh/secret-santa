<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard') | Secret Santa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0f172a; }
        .admin-sidebar { width: 240px; min-height: 100vh; background: rgba(15,23,42,0.95); border-right: 1px solid rgba(51,65,85,0.6); }
        .admin-nav-link { display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; color: #94a3b8; transition: background 0.15s, color 0.15s; }
        .admin-nav-link:hover, .admin-nav-link.active { background: rgba(245,158,11,0.12); color: #f59e0b; }
    </style>
</head>
<body class="min-h-screen text-slate-200 flex">

    <!-- Sidebar -->
    <aside class="admin-sidebar shrink-0 flex flex-col py-6 px-3 sticky top-0 h-screen overflow-y-auto">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 mb-8">
            <span class="text-2xl">🎅</span>
            <div>
                <div class="font-bold text-white text-sm leading-tight">Secret Santa</div>
                <div class="text-xs text-amber-400 font-medium">Admin Panel</div>
            </div>
        </a>

        <!-- Nav -->
        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('admin.dashboard') }}"
               class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('admin.users') }}"
               class="admin-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span>👥</span> Users
            </a>
            <a href="{{ route('admin.games') }}"
               class="admin-nav-link {{ request()->routeIs('admin.games') ? 'active' : '' }}">
                <span>🎮</span> Online Games
            </a>
        </nav>

        <!-- Bottom links -->
        <div class="border-t border-slate-700/50 pt-4 mt-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="admin-nav-link">
                <span>←</span> Back to App
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-nav-link w-full text-left text-red-400 hover:text-red-300">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="sticky top-0 z-10 flex items-center justify-between px-6 py-3 bg-slate-900/80 backdrop-blur border-b border-slate-700/50">
            <h1 class="text-sm font-semibold text-slate-200">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-3 text-sm text-slate-400">
                <span>👤 {{ Auth::user()->name }}</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 text-xs font-semibold">Admin</span>
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/30 text-green-300 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Page content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>