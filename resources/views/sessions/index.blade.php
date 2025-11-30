@extends('layouts.app')

@section('title', 'My Secret Santa Sessions')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        padding: 20px 0;
    }

    .session-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        border: 2px solid rgba(196, 30, 58, 0.1);
        transition: all 0.3s;
        margin-bottom: 1rem;
    }

    .session-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--santa-red);
    }

    .session-header {
        background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px 10px 0 0;
    }

    .session-body {
        padding: 1.5rem;
    }

    .session-code {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        background: rgba(196, 30, 58, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(10, 126, 58, 0.4);
    }

    .btn-outline-primary {
        border-color: var(--santa-green);
        color: var(--santa-green);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background: var(--santa-green);
        border-color: var(--santa-green);
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--santa-red);
        margin-bottom: 1rem;
    }

    .participant-count {
        background: var(--santa-gold);
        color: var(--santa-dark);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-active {
        background: rgba(10, 126, 58, 0.2);
        color: var(--santa-green);
    }

    .status-inactive {
        background: rgba(196, 30, 58, 0.2);
        color: var(--santa-red);
    }

    /* Christmas Tree Styles - positioned differently for sessions */
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .christmas-tree {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3><span class="gift-icon"></span>My Secret Santa Sessions</h3>
                    <p class="mb-0 mt-2">Manage your gift exchange sessions</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-0">Your Sessions ({{ $sessions->count() }})</h5>
                        </div>
                        <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Session
                        </a>
                    </div>

                    @if($sessions->count() > 0)
                        <div class="row">
                            @foreach($sessions as $session)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="session-card">
                                        <div class="session-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ $session->name }}</h6>
                                                <span class="participant-count">
                                                    <i class="fas fa-users me-1"></i>{{ $session->participants->count() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="session-body">
                                            <div class="mb-2">
                                                <strong>Code:</strong>
                                                <span class="session-code">{{ $session->code }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <span class="status-badge {{ $session->isActive() ? 'status-active' : 'status-inactive' }}">
                                                    @if($session->isActive())
                                                        <i class="fas fa-circle me-1"></i>Active
                                                    @else
                                                        <i class="fas fa-circle me-1"></i>Inactive
                                                    @endif
                                                </span>
                                            </div>
                                            @if($session->expires_at)
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Expires: {{ $session->expires_at->format('M j, Y g:i A') }}
                                                    </small>
                                                </div>
                                            @endif
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('sessions.show', $session) }}" class="btn btn-success flex-fill">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                <a href="{{ $session->shareable_link }}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fas fa-share me-1"></i>Share
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-gift"></i>
                            <h4>No Sessions Yet</h4>
                            <p class="mb-4">Create your first Secret Santa session to get started!</p>
                            <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Your First Session
                            </a>
                        </div>
                    @endif
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
