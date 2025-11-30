<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Santa Registration</title>
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            color: #333;
        }

        .card-header {
            background: linear-gradient(135deg, var(--santa-red) 0%, #e63946 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: 5px solid var(--santa-gold);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 126, 58, 0.4);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-success {
            background-color: rgba(10, 126, 58, 0.1);
            color: var(--santa-green);
            border-left: 4px solid var(--santa-green);
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

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .christmas-tree {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 80px;
            height: 100px;
        }

        .tree-trunk {
            position: absolute;
            bottom: 0;
            left: 35px;
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
            left: 15px;
        }

        .tree-level-2 {
            bottom: 45px;
            left: 15px;
            border-bottom-color: #0a7e3a;
        }

        .tree-level-3 {
            bottom: 70px;
            left: 15px;
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
            top: 10px;
            left: 35px;
            animation-delay: 0s;
        }

        .ornament-2 {
            top: 25px;
            left: 20px;
            background: var(--santa-gold);
            animation-delay: 0.5s;
        }

        .ornament-3 {
            top: 25px;
            left: 50px;
            background: var(--santa-gold);
            animation-delay: 1s;
        }

        .ornament-4 {
            top: 45px;
            left: 30px;
            animation-delay: 0.3s;
        }

        .ornament-5 {
            top: 45px;
            left: 45px;
            animation-delay: 0.8s;
        }

        .ornament-6 {
            top: 60px;
            left: 35px;
            background: var(--santa-gold);
            animation-delay: 1.2s;
        }

        .star {
            position: absolute;
            top: 5px;
            left: 35px;
            color: var(--santa-gold);
            font-size: 12px;
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
        <div class="ornament ornament-6"></div>
        <div class="star">★</div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header position-relative">
                        <div class="santa-hat"></div>
                        <h3><span class="gift-icon"></span>Secret Santa Registration</h3>
                        <p class="mb-0 mt-2">Join the festive gift exchange!</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required placeholder="Enter your full name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" required placeholder="Enter your email address">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    placeholder="Create a secure password">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required placeholder="Re-enter your password">
                            </div>

                            <button type="submit" class="btn btn-primary">Join Secret Santa</button>
                        </form>

                        <div class="footer-text">
                            Already have an account? <a href="#" style="color: var(--santa-gold);">Sign in
                                here</a>
                        </div>
                    </div>
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

        document.addEventListener('DOMContentLoaded', createSnowflakes);
    </script>
</body>

</html>
