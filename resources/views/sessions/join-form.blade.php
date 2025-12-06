@extends('layouts.app')

@section('title', 'Join Secret Santa Session')

@push('styles')
<style>
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

    /* Christmas Tree Styles */
    .christmas-tree {
        position: fixed;
        bottom: 20px;
        right: 30px;
        width: 100px;
        height: 130px;
        z-index: -1;
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
        top: 5px;
        left: 47px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="container d-flex align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-4">
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

                    @if($session->rule)
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Rules:</strong> {{ $session->rule }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="">
                            <i class="gift-icon"></i>
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
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
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
</div>
@endsection

@push('scripts')
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
@endpush
