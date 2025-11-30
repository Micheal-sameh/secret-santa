<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Secret Santa Assignment</title>
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
            position: relative;
            overflow-x: hidden;
        }

        .assignment-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: 4px solid var(--santa-gold);
            position: relative;
            overflow: hidden;
        }

        .assignment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, var(--santa-red), var(--santa-green), var(--santa-gold));
        }

        .snowflake {
            position: absolute;
            color: white;
            font-size: 1.5rem;
            opacity: 0.7;
            z-index: -1;
            animation: fall linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }

        .gift-icon {
            font-size: 5rem;
            color: var(--santa-red);
            margin-bottom: 1rem;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .recipient-name {
            font-size: 2.8rem;
            font-weight: bold;
            color: var(--santa-dark);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, var(--santa-red), var(--santa-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 0.5rem 1.5rem;
            border-radius: 15px;
            display: inline-block;
            margin: 1rem 0;
        }

        .secret-reminder {
            background: linear-gradient(135deg, rgba(196, 30, 58, 0.1), rgba(10, 126, 58, 0.1));
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 2px dashed var(--santa-red);
            position: relative;
        }

        .secret-reminder::before {
            content: '🎅';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 10px;
            font-size: 1.5rem;
        }

        .back-btn {
            background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(10, 126, 58, 0.4);
            color: white;
            text-decoration: none;
        }

        .header-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: swing 3s ease-in-out infinite;
        }

        @keyframes swing {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }

        .confetti {
            position: absolute;
            width: 15px;
            height: 15px;
            opacity: 0.7;
            z-index: -1;
            animation: confettiFall 5s linear infinite;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-100px) rotate(0deg);
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
            }
        }

        .assignment-badge {
            background: var(--santa-gold);
            color: var(--santa-dark);
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .santa-hat {
            position: absolute;
            top: -30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--santa-red);
            border-radius: 50% 50% 0 0;
            transform: rotate(15deg);
        }

        .santa-hat::before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 20px;
            background: white;
            border-radius: 10px;
        }

        .santa-hat::after {
            content: "";
            position: absolute;
            top: 15px;
            right: -10px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }

        .footer-note {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <!-- Snowflakes -->
    <div id="snowflakes"></div>

    <!-- Confetti -->
    <div id="confetti"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="assignment-card p-5 text-center position-relative">
                    <div class="santa-hat"></div>

                    <div class="header-icon">🎄</div>
                    <h1 class="mb-4 text-success fw-bold">Ho Ho Ho!</h1>

                    <div class="assignment-badge">
                        <i class="fas fa-user me-2"></i>Hello, {{ $participant->name }}!
                    </div>

                    <div class="mb-4">
                        <p class="lead mb-3 fs-5">Your Secret Santa assignment is:</p>
                        <div class="recipient-name">
                            {{ $assignment->recipient->name }}
                        </div>
                        <p class="text-muted mt-3">
                            <i class="fas fa-gift me-2"></i>Time to find the perfect gift!
                        </p>
                    </div>

                    <div class="secret-reminder">
                        <h5 class="mb-3"><i class="fas fa-shield-alt me-2"></i>Top Secret Assignment</h5>
                        <p class="mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Remember to keep your assignment a secret until the gift exchange.
                            Don't tell anyone who you're buying for!
                        </p>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('sessions.show', $session) }}" class="back-btn">
                            <i class="fas fa-arrow-left me-2"></i>Back to Session
                        </a>
                    </div>
                </div>

                <div class="footer-note">
                    <p class="mb-0">
                        <i class="fas fa-tree me-2"></i>Spread the holiday cheer with thoughtful gifts!
                        <i class="fas fa-gift ms-2"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Create snowflakes
        function createSnowflakes() {
            const snowflakeContainer = document.getElementById('snowflakes');
            const snowflakeCount = 40;

            for (let i = 0; i < snowflakeCount; i++) {
                const snowflake = document.createElement('div');
                snowflake.classList.add('snowflake');
                snowflake.innerHTML = '❄';

                // Random properties
                const size = Math.random() * 1.5 + 0.5;
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

        // Create confetti
        function createConfetti() {
            const confettiContainer = document.getElementById('confetti');
            const confettiCount = 30;
            const colors = ['#c41e3a', '#0a7e3a', '#ffd700', '#ffffff'];

            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.classList.add('confetti');

                // Random properties
                const size = Math.random() * 10 + 5;
                const left = Math.random() * 100;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const animationDuration = Math.random() * 5 + 3;
                const animationDelay = Math.random() * 5;
                const shape = Math.random() > 0.5 ? '0%' : '50%';

                confetti.style.left = `${left}vw`;
                confetti.style.width = `${size}px`;
                confetti.style.height = `${size}px`;
                confetti.style.backgroundColor = color;
                confetti.style.borderRadius = shape;
                confetti.style.animationDuration = `${animationDuration}s`;
                confetti.style.animationDelay = `${animationDelay}s`;

                confettiContainer.appendChild(confetti);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            createSnowflakes();
            createConfetti();
        });
    </script>
</body>
</html>