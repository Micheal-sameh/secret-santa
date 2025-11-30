@extends('layouts.app')

@section('content')
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
                            <h3>{{ $assignment->recipient->name }} </h3>
                        </div>
                    </div>

                    <div class="secret-reminder">
                        <h5 class="mb-2">🤫 Keep it Secret!</h5>
                        <p class="mb-0">Remember to keep your assignment a secret until the gift exchange. Don't tell
                            anyone who you're buying for!</p>
                    </div>
                    @auth
                        <div class="mt-4">
                            <a href="{{ route('sessions.show', $session) }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Session
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

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
@endsection

@push('scripts')
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
@endpush
