<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Secret Santa Assignment - {{ $session->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --santa-red: #c41e3a;
            --santa-green: #0a7e3a;
            --santa-gold: #ffd700;
            --santa-dark: #1a1a2e;
        }

        body {
            background: linear-gradient(135deg, var(--santa-dark) 0%, #16213e 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            color: #333;
        }

        .card-header {
            background: linear-gradient(135deg, var(--santa-red) 0%, #e63946 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: 4px solid var(--santa-gold);
        }

        .card-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .card-body {
            padding: 2rem;
        }

        .assignment-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 3px solid var(--santa-red);
            position: relative;
            overflow: hidden;
        }

        .assignment-card::before {
            content: '🎄';
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 2rem;
            transform: rotate(15deg);
        }

        .gift-icon {
            font-size: 4rem;
            color: var(--santa-red);
            margin-bottom: 1rem;
        }

        .recipient-name {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--santa-dark);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .secret-reminder {
            background: linear-gradient(45deg, rgba(196, 30, 58, 0.1), rgba(254, 207, 239, 0.1));
            border-radius: 15px;
            padding: 1rem;
            margin-top: 2rem;
            border: 2px solid var(--santa-red);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 126, 58, 0.4);
        }

        .snowflake {
            position: fixed;
            color: white;
            font-size: 1rem;
            opacity: 0.6;
            z-index: -1;
            animation: fall linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }

        /* Christmas Tree Styles */
        .christmas-tree {
            position: fixed;
            bottom: 20px;
            left: 30px;
            width: 100px;
            height: 130px;
            z-index: -1;
        }

        .tree-trunk {
            position: absolute;
            bottom: 0;
            left: 45px;
            width: 10px;
            height: 20px;
            background: #8B4513;
            border-radius: 2px;
        }

        .tree-level {
            position: absolute;
            width: 0;
            height: 0;
            border-left: 25px solid transparent;
            border-right: 25px solid transparent;
            border-bottom: 40px solid var(--santa-green);
        }

        .tree-level-1 {
            bottom: 20px;
            left: 25px;
        }

        .tree-level-2 {
            bottom: 45px;
            left: 25px;
            border-bottom-color: #0a7e3a;
        }

        .tree-level-3 {
            bottom: 70px;
            left: 25px;
            border-bottom-color: #0a6e32;
        }

        .ornament {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--santa-red);
            z-index: 1;
            animation: twinkle 2s infinite alternate;
        }

        .ornament-1 {
            top: 15px;
            left: 48px;
            animation-delay: 0s;
        }

        .ornament-2 {
            top: 35px;
            left: 35px;
            background: var(--santa-gold);
            animation-delay: 0.5s;
        }

        .ornament-3 {
            top: 35px;
            left: 60px;
            background: var(--santa-gold);
            animation-delay: 1s;
        }

        .ornament-4 {
            top: 55px;
            left: 42px;
            animation-delay: 0.3s;
        }

        .ornament-5 {
            top: 55px;
            left: 55px;
            animation-delay: 0.8s;
        }

        .star {
            position: absolute;
            top: 5px;
            left: 47px;
            color: var(--santa-gold);
            font-size: 14px;
            z-index: 1;
            animation: spin 4s linear infinite;
            text-shadow: 0 0 8px var(--santa-gold);
        }

        @keyframes twinkle {
            0% { opacity: 0.3; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1.1); }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .tree-gift {
            position: absolute;
            width: 25px;
            height: 25px;
            background: var(--santa-red);
            border-radius: 5px;
            bottom: -5px;
            left: 60px;
            animation: bounce 3s ease-in-out infinite;
        }

        .tree-gift::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-gold);
            clip-path: polygon(0 40%, 100% 40%, 100% 60%, 0 60%);
        }

        .tree-gift::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-gold);
            clip-path: polygon(40% 0, 60% 0, 60% 100%, 40% 100%);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .christmas-tree {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Simple Snowflakes -->
    <div id="snowflakes"></div>

    <!-- Christmas Tree -->
    <div class="christmas-tree">
        <div class="tree-level tree-level-3"></div>
        <div class="tree-level tree-level-2"></div>
        <div class="tree-level tree-level-1"></div>
        <div class="tree-trunk"></div>
        <div class="ornament ornament-1"></div>
        <div class="ornament ornament-2"></div>
        <div class="ornament ornament-3"></div>
        <div class="ornament ornament-4"></div>
        <div class="ornament ornament-5"></div>
        <div class="star">★</div>
        <div class="tree-gift"></div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="assignment-card p-5 text-center">
                    <div class="gift-icon">🎁</div>

                    <h1 class="mb-4" style="color: var(--santa-red);">🎄 Ho Ho Ho! 🎄</h1>
                    <h2 class="mb-3">Hello, {{ $participant->name }}!</h2>

                    <div class="mb-4">
                        <p class="lead mb-3">Your Secret Santa assignment is:</p>
                        <div class="recipient-name">
                            {{ $assignment->recipient->name }}
                        </div>
                    </div>

                    <div class="secret-reminder">
                        <h5 class="mb-2">🤫 Keep it Secret!</h5>
                        <p class="mb-0">Remember to keep your assignment a secret until the gift exchange. Don't tell anyone who you're buying for!</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('sessions.show', $session) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Session
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple snowflakes
        function createSnowflakes() {
            const snowflakeContainer = document.getElementById('snowflakes');
            const snowflakeCount = 30;

            for (let i = 0; i < snowflakeCount; i++) {
                const snowflake = document.createElement('div');
                snowflake.classList.add('snowflake');
                snowflake.innerHTML = '❄';

                const size = Math.random() * 1 + 0.5;
                const left = Math.random() * 100;
                const animationDuration = Math.random() * 10 + 5;
                const animationDelay = Math.random() * 5;

                snowflake.style.left = `${left}vw`;
                snowflake.style.fontSize = `${size}rem`;
                snowflake.style.animationDuration = `${animationDuration}s`;
                snowflake.style.animationDelay = `${animationDelay}s`;

                snowflakeContainer.appendChild(snowflake);
            }
        }

        document.addEventListener('DOMContentLoaded', createSnowflakes);
    </script>
</body>
</html>
