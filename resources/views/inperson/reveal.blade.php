<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>🎁 Your Secret Santa Assignment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: radial-gradient(ellipse at top, #3b0764 0%, #1a0033 30%, #0f172a 70%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* Confetti */
        .confetti-piece {
            position: fixed;
            width: 8px; height: 8px;
            top: -20px;
            animation: fall linear infinite;
            border-radius: 2px;
            pointer-events: none;
        }
        @keyframes fall {
            0%   { transform: translateY(0) rotate(0deg); opacity:1; }
            80%  { opacity: 1; }
            100% { transform: translateY(105vh) rotate(720deg); opacity:0; }
        }
        /* Card flip */
        .reveal-card {
            perspective: 1200px;
        }
        .reveal-card-inner {
            transform-style: preserve-3d;
            animation: flipIn 0.8s cubic-bezier(0.25,0.46,0.45,0.94) forwards;
        }
        @keyframes flipIn {
            0%   { transform: rotateY(90deg) scale(0.8); opacity:0; }
            100% { transform: rotateY(0deg)  scale(1);   opacity:1; }
        }
        /* Glow pulse */
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 30px rgba(245,158,11,0.3), 0 0 60px rgba(220,38,38,0.2); }
            50%       { box-shadow: 0 0 50px rgba(245,158,11,0.5), 0 0 100px rgba(220,38,38,0.3); }
        }
        .glow-animate { animation: glowPulse 2.5s ease-in-out infinite; }

        /* Stars */
        .star {
            position: fixed;
            background: white;
            border-radius: 50%;
            pointer-events: none;
        }

        /* Name text gradient */
        .name-gradient {
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #dc2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4 relative">

    <!-- Stars background -->
    <div id="stars" aria-hidden="true"></div>
    <!-- Confetti -->
    <div id="confetti" aria-hidden="true"></div>

    <!-- Main reveal card -->
    <div class="reveal-card relative z-10 w-full max-w-sm sm:max-w-md">
        <div class="reveal-card-inner glow-animate rounded-3xl overflow-hidden"
             style="background: linear-gradient(135deg, #1e1035 0%, #1e293b 60%, #0f172a 100%);
                    border: 1px solid rgba(245,158,11,0.3);">

            <!-- Top decoration -->
            <div class="relative h-2 bg-gradient-to-r from-red-600 via-amber-400 to-red-600"></div>

            <div class="p-8 sm:p-10 text-center">

                <!-- Santa icon -->
                <div class="text-6xl sm:text-7xl mb-4 animate-bounce" style="animation-duration:2s">🎅</div>

                <!-- Hi greeting -->
                <p class="text-slate-400 text-sm uppercase tracking-widest font-semibold mb-1">
                    Hello, {{ $participant->name }}!
                </p>
                <h1 class="text-white font-bold text-xl sm:text-2xl mb-6">
                    Your Secret Santa assignment is…
                </h1>

                <!-- The big reveal -->
                <div class="relative py-6">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-red-500/10 rounded-2xl"></div>
                    <div class="relative">
                        <div class="text-4xl mb-3">🎁</div>
                        <div class="name-gradient text-4xl sm:text-5xl font-black leading-tight mb-2">
                            {{ $participant->assigned_to }}
                        </div>
                    </div>
                </div>

                @if($game->price_limit)
                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-sm font-medium">
                    💰 Gift budget: up to ${{ number_format($game->price_limit, 2) }}
                </div>
                @endif

                <div class="mt-6 p-3 rounded-xl bg-white/5 text-slate-400 text-xs leading-relaxed">
                    🤫 <strong class="text-slate-300">Keep this secret!</strong>
                    Don't tell anyone who you got — it's part of the fun!
                </div>
            </div>

            <!-- Bottom decoration -->
            <div class="relative h-2 bg-gradient-to-r from-red-600 via-amber-400 to-red-600"></div>
        </div>
    </div>

    <!-- Back button -->
    <div class="relative z-10 mt-8 text-center">
        <a href="{{ route('inperson.show', $game->device_token) }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-600 text-slate-300 hover:text-white text-sm font-medium transition-all">
            ✅ Done — Next Person
        </a>
    </div>

    <script>
        // Stars
        (function() {
            const container = document.getElementById('stars');
            for (let i = 0; i < 60; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                const size = Math.random() * 2.5 + 0.5;
                star.style.width  = size + 'px';
                star.style.height = size + 'px';
                star.style.left   = Math.random() * 100 + 'vw';
                star.style.top    = Math.random() * 100 + 'vh';
                star.style.opacity = (0.2 + Math.random() * 0.7).toString();
                container.appendChild(star);
            }
        })();

        // Confetti
        (function() {
            const colors = ['#dc2626','#f59e0b','#16a34a','#3b82f6','#8b5cf6','#ec4899','#fbbf24'];
            const container = document.getElementById('confetti');
            for (let i = 0; i < 60; i++) {
                const el = document.createElement('div');
                el.className = 'confetti-piece';
                el.style.left             = Math.random() * 100 + 'vw';
                el.style.backgroundColor  = colors[Math.floor(Math.random() * colors.length)];
                el.style.animationDuration = (3 + Math.random() * 5) + 's';
                el.style.animationDelay   = (Math.random() * 3) + 's';
                el.style.width  = (6 + Math.random() * 6) + 'px';
                el.style.height = (6 + Math.random() * 6) + 'px';
                el.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
                el.style.opacity = (0.7 + Math.random() * 0.3).toString();
                container.appendChild(el);
            }
        })();
    </script>
</body>
</html>
