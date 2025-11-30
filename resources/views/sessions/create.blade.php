<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Secret Santa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path fill="%23ffffff" opacity="0.03" d="M50,20 C65,20 77,32 77,47 C77,62 65,74 50,74 C35,74 23,62 23,47 C23,32 35,20 50,20 Z M50,30 C40,30 32,38 32,48 C32,58 40,66 50,66 C60,66 68,58 68,48 C68,38 60,30 50,30 Z"/></svg>');
            z-index: -1;
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
                transform: translateY(100vh);
            }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            color: #333;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--santa-red) 0%, #e63946 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: 5px solid var(--santa-gold);
            position: relative;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--santa-dark);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--santa-red);
            box-shadow: 0 0 0 0.25rem rgba(196, 30, 58, 0.25);
        }

        .form-text {
            color: #6c757d;
            font-style: italic;
            margin-top: 0.25rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 126, 58, 0.4);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-danger {
            background-color: rgba(196, 30, 58, 0.1);
            color: var(--santa-red);
            border-left: 4px solid var(--santa-red);
        }

        .santa-hat {
            position: absolute;
            top: -30px;
            right: 20px;
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

        .gift-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: var(--santa-red);
            position: relative;
            margin-right: 8px;
            vertical-align: middle;
        }

        .gift-icon::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-green);
            clip-path: polygon(0 40%, 100% 40%, 100% 60%, 0 60%);
        }

        .gift-icon::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-green);
            clip-path: polygon(40% 0, 60% 0, 60% 100%, 40% 100%);
        }

        .session-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--santa-gold);
            border-radius: 50%;
            margin-right: 10px;
            color: var(--santa-dark);
            font-weight: bold;
        }

        .footer-note {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .floating-gift {
            position: absolute;
            width: 60px;
            height: 60px;
            background: var(--santa-red);
            border-radius: 8px;
            transform: rotate(15deg);
            animation: float 3s ease-in-out infinite;
            z-index: 0;
        }

        .floating-gift::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-green);
            clip-path: polygon(0 40%, 100% 40%, 100% 60%, 0 60%);
        }

        .floating-gift::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--santa-green);
            clip-path: polygon(40% 0, 60% 0, 60% 100%, 40% 100%);
        }

        .gift-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .gift-2 {
            bottom: 20%;
            right: 10%;
            animation-delay: 1.5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(15deg);
            }

            50% {
                transform: translateY(-10px) rotate(15deg);
            }
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.7);
            border-left: 4px solid var(--santa-red);
        }

        .form-section-title {
            font-weight: 600;
            color: var(--santa-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Snowflakes -->
    <div id="snowflakes"></div>

    <!-- Floating Gifts -->
    <div class="floating-gift gift-1"></div>
    <div class="floating-gift gift-2"></div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header position-relative">
                        <div class="santa-hat"></div>
                        <h3><span class="gift-icon"></span>Create New Secret Santa</h3>
                        <p class="mb-0 mt-2">Organize your gift exchange with friends and family</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('sessions.store') }}">
                            @csrf

                            <div class="form-section">
                                <div class="form-section-title">
                                    <span class="session-icon">🎁</span>
                                    Session Details
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Session Name *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required placeholder="e.g., Family Christmas 2023">
                                    <div class="form-text">Give your Secret Santa a fun name</div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="form-section-title">
                                    <span class="session-icon">⏰</span>
                                    Session Settings
                                </div>

                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Gift Exchange Date</label>
                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at"
                                        value="{{ old('expires_at') }}">
                                    <div class="form-text">When should this Secret Santa end? (Optional)</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <strong>Create Secret Santa</strong>
                                </button>
                                <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="footer-note">
                    <p>🎄 Spread the holiday cheer with a well-organized Secret Santa exchange! 🎄</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Create snowflakes
        function createSnowflakes() {
            const snowflakeContainer = document.getElementById('snowflakes');
            const snowflakeCount = 50;

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

        // Set default expiration to one week from now
        document.addEventListener('DOMContentLoaded', function() {
            createSnowflakes();

            // Set default expiration date to one week from now
            const now = new Date();
            now.setDate(now.getDate() + 7);
            const defaultDate = now.toISOString().slice(0, 16);

            const expiresAtField = document.getElementById('expires_at');
            if (expiresAtField && !expiresAtField.value) {
                expiresAtField.value = defaultDate;
            }
        });
    </script>
</body>

</html>
