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
                            red:   '#dc2626',
                            green: '#16a34a',
                            gold:  '#f59e0b',
                            dark:  '#0f172a',
                            card:  '#1e293b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: radial-gradient(ellipse at top, #1a1033 0%, #0f172a 60%);
            min-height: 100vh;
        }
        .snowflake {
            position: fixed; top: -20px; pointer-events: none; z-index: 0;
            animation: snowfall linear infinite; color: rgba(255,255,255,0.4);
        }
        @keyframes snowfall {
            0%   { transform: translateY(-20px) rotate(0deg); opacity: 0.7; }
            100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    <div id="snowflakes" aria-hidden="true"></div>

    <!-- Card -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex flex-col items-center gap-2">
                <span class="text-5xl">🎅</span>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-white tracking-tight">
                        Secret<span class="text-amber-400">Santa</span>
                    </span>
                    <span class="text-xs text-slate-400 font-medium">by {{config('ceompany.name')}}</span>
                </div>
            </a>
        </div>

        <!-- Form card -->
        <div class="bg-slate-800/90 backdrop-blur border border-slate-700/60 rounded-2xl p-8 shadow-2xl">
            @yield('content')
        </div>

        <!-- Footer link -->
        <p class="text-center mt-6 text-slate-500 text-sm">
            @yield('footer-link')
        </p>
    </div>

    <script>
        (function() {
            const container = document.getElementById('snowflakes');
            for (let i = 0; i < 12; i++) {
                const el = document.createElement('span');
                el.className = 'snowflake';
                el.textContent = ['❄','❅','❆'][Math.floor(Math.random()*3)];
                el.style.left = Math.random() * 100 + 'vw';
                el.style.fontSize = (0.5 + Math.random()) + 'rem';
                el.style.animationDuration = (10 + Math.random() * 15) + 's';
                el.style.animationDelay    = (Math.random() * 10) + 's';
                container.appendChild(el);
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
