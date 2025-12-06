@extends('layouts.app')

@section('title', 'Check Your Secret Santa Assignment - ' . $session->name)

@section('content')
<div class="container  d-flex align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3><span class="gift-icon"></span>Check Your Secret Santa Drawing</h3>
                    <p class="mb-0 mt-2">{{ $session->name }}</p>
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
                        <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                        <h4>Find Out Who You're Buying For!</h4>
                        <p class="text-muted">Enter your name exactly as you registered to see your Secret Santa Drwaing.</p>
                    </div>

                    @if($session->rule)
                        <div class="alert alert-info">
                            <strong>Session Rules:</strong> {{ $session->rule }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sessions.show-assignment', $session) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="participant_name" class="form-label fw-bold">Your Name</label>
                            <input type="text" class="form-control form-control-lg" id="participant_name" name="participant_name"
                                   value="{{ old('participant_name') }}" placeholder="Enter your full name" required>
                            <div class="form-text">
                                <small class="text-muted">Make sure to spell your name exactly as you entered it when joining the session.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Reveal My Drawing
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('sessions.show', $session) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Session
                        </a>
                    </div>
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
