<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Secret Santa Session</title>
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

        .form-label {
            color: var(--santa-dark);
            font-weight: 600;
            margin-bottom: 0.75rem;
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
            margin-top: 0.5rem;
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

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
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

        .gift-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: var(--santa-red);
            position: relative;
            margin-right: 8px;
            vertical-align: middle;
            border-radius: 3px;
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

        .christmas-tree {
            position: fixed;
            bottom: 20px;
            right: 30px;
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
        }

        @keyframes twinkle {
            0% { opacity: 0.3; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1.1); }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .join-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: var(--santa-gold);
            border-radius: 50%;
            margin-bottom: 1rem;
            color: var(--santa-dark);
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .christmas-tree {
                display: none;
            }
        }

        .santa-hat {
            position: absolute;
            top: -25px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: var(--santa-red);
            border-radius: 50% 50% 0 0;
            transform: rotate(15deg);
        }

        .santa-hat::before {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 16px;
            background: white;
            border-radius: 8px;
        }

        .santa-hat::after {
            content: "";
            position: absolute;
            top: 12px;
            right: -8px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <!-- Snowflakes -->
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
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header position-relative">
                        <div class="santa-hat"></div>
                        <h3><span class="gift-icon"></span>Join Secret Santa Session</h3>
                        <p class="mb-0 mt-2">Enter your name to join the gift exchange</p>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="join-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <p class="text-muted">Get ready for some festive gift-giving fun!</p>
                        </div>

                        <form method="POST" action="{{ route('session.join') }}">
                            @csrf
                            <input type="hidden" name="code" value="{{ $code }}">
                            <div class="mb-4">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Your Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name') }}" maxlength="255" required
                                       placeholder="Enter your name">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    This is how you'll appear in the Secret Santa assignments
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-gift me-2"></i>Join Session
                                </button>
                                <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
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
            const snowflakeCount = 25;

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