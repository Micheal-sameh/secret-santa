<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Secret Santa') 🎅</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        santa: {
                            red:    '#dc2626',
                            green:  '#16a34a',
                            gold:   '#f59e0b',
                            dark:   '#0f172a',
                            card:   '#1e293b',
                            border: '#334155',
                        }
                    },
                    animation: {
                        'snow':   'snowfall linear infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        snowfall: {
                            '0%':   { transform: 'translateY(-20px) rotate(0deg)', opacity: '0.8' },
                            '100%': { transform: 'translateY(100vh) rotate(360deg)', opacity: '0' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f172a; }

        /* Snowflakes */
        .snowflake {
            position: fixed;
            top: -20px;
            pointer-events: none;
            z-index: 0;
            animation: snowfall linear infinite;
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
            user-select: none;
        }
        @keyframes snowfall {
            0%   { transform: translateY(-20px) translateX(0) rotate(0deg); opacity: 0.7; }
            50%  { transform: translateY(50vh)  translateX(30px) rotate(180deg); opacity: 0.5; }
            100% { transform: translateY(105vh) translateX(-20px) rotate(360deg); opacity: 0; }
        }

        /* Glassmorphism cards */
        .glass-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.6);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }

        /* Flash messages */
        .flash-success { background: rgba(22,163,74,0.15); border-left: 4px solid #16a34a; }
        .flash-error   { background: rgba(220,38,38,0.15); border-left: 4px solid #dc2626; }

        /* Nav active */
        .nav-link { transition: color 0.2s, background 0.2s; }
        .nav-link:hover { color: #f59e0b; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen text-slate-200 relative overflow-x-hidden">

    <!-- Snowflakes -->
    <div id="snowflakes" aria-hidden="true"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 inset-x-0 z-50 glass-card border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl group-hover:animate-bounce-slow">🎅</span>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg tracking-tight text-white">
                            Secret<span class="text-santa-gold">Santa</span>
                        </span>
                        <span class="text-xs text-slate-400 font-medium">by Crafando</span>
                    </div>
                </a>

                <!-- Desktop nav -->
                <div class="hidden md:flex items-center gap-1">
                        @auth
                        <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700/60">
                            🎄 Dashboard
                        </a>
                        <a href="{{ route('games.create') }}" class="nav-link px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700/60">
                            ➕ New Game
                        </a>
                    @endauth
                    <a href="{{ route('inperson.create') }}" class="nav-link px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700/60">
                        🎁 In-Person
                    </a>
                </div>

                <!-- User menu -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex items-center gap-2">
                            @if(Auth::user()->avatar)
                                <a href="{{ route('profile') }}">
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                         class="w-8 h-8 rounded-full ring-2 ring-santa-gold/40 hover:ring-santa-gold transition-all">
                                </a>
                            @else
                                <a href="{{ route('profile') }}"
                                   class="w-8 h-8 rounded-full bg-gradient-to-br from-santa-red to-santa-gold flex items-center justify-center text-sm font-bold text-white hover:opacity-80 transition-opacity">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </a>
                            @endif
                            <a href="{{ route('profile') }}" class="text-sm text-slate-300 hidden lg:block hover:text-amber-400 transition-colors">{{ Auth::user()->name }}</a>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-xs px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-santa-red/80 text-slate-300 hover:text-white transition-colors">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700/60 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm px-4 py-1.5 rounded-lg bg-santa-red hover:bg-red-700 text-white font-medium transition-colors">
                            Sign Up
                        </a>
                    @endguest

                    <!-- Mobile hamburger -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-700/50 py-2 px-4 space-y-1">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 text-sm">
                    🎄 Dashboard
                </a>
                <a href="{{ route('games.create') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 text-sm">
                    ➕ New Game
                </a>
                <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 text-sm">
                    👤 My Profile
                </a>
            @endauth
            <a href="{{ route('inperson.create') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 text-sm">
                🎁 In-Person Game
            </a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="relative z-10 pt-20 min-h-screen">

        <!-- Flash messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="flash-success rounded-lg p-4 text-green-300 text-sm flex items-start gap-2">
                    <span class="text-base mt-0.5">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="flash-error rounded-lg p-4 text-red-300 text-sm flex items-start gap-2">
                    <span class="text-base mt-0.5">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-800 mt-16 py-8 text-center text-slate-500 text-sm">
        <p>🎅 Secret Santa &copy; {{ date('Y') }} — Spreading holiday joy</p>
    </footer>

    <script>
        // Snowflakes
        (function() {
            const container = document.getElementById('snowflakes');
            const flakes = ['❄', '❅', '❆', '✦', '✧'];
            for (let i = 0; i < 20; i++) {
                const el = document.createElement('span');
                el.className = 'snowflake';
                el.textContent = flakes[Math.floor(Math.random() * flakes.length)];
                el.style.left     = Math.random() * 100 + 'vw';
                el.style.fontSize = (0.6 + Math.random() * 1.2) + 'rem';
                el.style.animationDuration = (8 + Math.random() * 14) + 's';
                el.style.animationDelay    = (Math.random() * 12) + 's';
                el.style.opacity = (0.3 + Math.random() * 0.5).toString();
                container.appendChild(el);
            }
        })();

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>
