@extends('layouts.app')

@section('title', 'My Secret Santa Sessions')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --santa-red: #c41e3a;
        --santa-green: #0a7e3a;
        --santa-gold: #ffd700;
        --santa-dark: #1a1a2e;
        --santa-light: #f8f9fa;
    }

    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px 0;
        min-height: 100vh;
    }

    /* Main Card */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 1rem;
    }

    .card-header {
        background: linear-gradient(135deg, var(--santa-dark) 0%, #16213e 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
        position: relative;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--santa-red), var(--santa-gold), var(--santa-green));
    }

    .card-header h3 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header h3 i {
        color: var(--santa-gold);
    }

    .card-header p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .session-count {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--santa-dark);
    }

    /* Create Button */
    .btn-create {
        background: linear-gradient(135deg, var(--santa-red) 0%, #e63946 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 5px 15px rgba(196, 30, 58, 0.2);
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(196, 30, 58, 0.3);
        color: white;
    }

    /* Session Cards */
    .sessions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .session-card {
        background: white;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .session-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-color: var(--santa-green);
    }

    .session-header {
        background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .session-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .session-card:hover .session-header::before {
        transform: translateX(100%);
    }

    .session-header h6 {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .participant-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .session-body {
        padding: 1.5rem;
    }

    .session-code {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: linear-gradient(135deg, var(--santa-dark), #2d3748);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        border: none;
    }

    .session-code:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .copy-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--santa-dark);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        margin-bottom: 0.5rem;
    }

    .session-code:hover .copy-tooltip {
        opacity: 1;
        visibility: visible;
    }

    .session-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .status-badge {
        padding: 0.4rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid transparent;
    }

    .status-active {
        background: rgba(10, 126, 58, 0.1);
        color: var(--santa-green);
        border-color: rgba(10, 126, 58, 0.2);
    }

    .status-inactive {
        background: rgba(196, 30, 58, 0.1);
        color: var(--santa-red);
        border-color: rgba(196, 30, 58, 0.2);
    }

    .session-expiry {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.9rem;
    }

    .session-expiry i {
        color: var(--santa-gold);
    }

    /* Action Buttons */
    .session-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--santa-green) 0%, #2a9d8f 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        text-align: center;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(10, 126, 58, 0.3);
        color: white;
    }

    .btn-share {
        background: transparent;
        border: 2px solid var(--santa-blue);
        color: var(--santa-blue);
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        text-align: center;
    }

    .btn-share:hover {
        background: var(--santa-blue);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        border: 2px dashed #e9ecef;
        margin: 2rem 0;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--santa-red);
        margin-bottom: 1.5rem;
        display: inline-block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h4 {
        color: var(--santa-dark);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Success Alert */
    .alert-success {
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, rgba(10, 126, 58, 0.1) 0%, rgba(10, 126, 58, 0.05) 100%);
        border-left: 4px solid var(--santa-green);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success i {
        color: var(--santa-green);
        font-size: 1.25rem;
    }

    /* Christmas Tree */
    .christmas-tree {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 120px;
        height: 150px;
        z-index: -1;
        opacity: 0.7;
        pointer-events: none;
    }

    .tree-level {
        position: absolute;
        width: 0;
        height: 0;
        border-left: 40px solid transparent;
        border-right: 40px solid transparent;
        border-bottom: 60px solid var(--santa-green);
        bottom: 20px;
        left: 20px;
    }

    .tree-level-2 {
        border-bottom-color: #0a6e32;
        bottom: 45px;
        left: 25px;
        border-left-width: 30px;
        border-right-width: 30px;
    }

    .tree-level-3 {
        border-bottom-color: #0a5e2a;
        bottom: 70px;
        left: 30px;
        border-left-width: 20px;
        border-right-width: 20px;
    }

    .tree-trunk {
        position: absolute;
        width: 20px;
        height: 25px;
        background: #8b4513;
        bottom: 0;
        left: 50px;
        border-radius: 4px;
    }

    .ornament {
        position: absolute;
        width: 12px;
        height: 12px;
        background: var(--santa-red);
        border-radius: 50%;
        animation: twinkle 2s infinite;
    }

    .ornament-1 { top: 20px; left: 50px; animation-delay: 0s; }
    .ornament-2 { top: 40px; left: 35px; background: var(--santa-gold); animation-delay: 0.5s; }
    .ornament-3 { top: 40px; left: 65px; background: var(--santa-gold); animation-delay: 1s; }
    .ornament-4 { top: 60px; left: 45px; animation-delay: 0.3s; }
    .ornament-5 { top: 60px; left: 55px; animation-delay: 0.8s; }

    .star {
        position: absolute;
        top: 10px;
        left: 55px;
        color: var(--santa-gold);
        font-size: 18px;
        animation: twinkle 1.5s infinite;
    }

    @keyframes twinkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }

    /* Snowflakes */
    .snowflakes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -2;
    }

    .snowflake {
        position: absolute;
        color: #4cc9f0;
        font-size: 1em;
        opacity: 0.5;
        animation: fall linear infinite;
    }

    @keyframes fall {
        to {
            transform: translateY(100vh);
        }
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: var(--santa-dark);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        transform: translateX(-100px);
        opacity: 0;
        transition: all 0.5s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .toast-notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-icon {
        color: var(--santa-gold);
        font-size: 1.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .christmas-tree {
            display: none;
        }

        .sessions-grid {
            grid-template-columns: 1fr;
        }

        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-create {
            width: 100%;
            justify-content: center;
        }

        .session-actions {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .session-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .card-header {
            padding: 1.25rem;
        }

        .card-header h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Snowflakes -->
<div id="snowflakes" class="snowflakes"></div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header">
                    <h3><i class="fas fa-gift"></i>My Secret Santa Sessions</h3>
                    <p class="mb-0 mt-2">Manage your gift exchange sessions with friends and family</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            <i class="fas fa-check-circle"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <div class="header-actions">
                        <div>
                            <div class="session-count">
                                Your Sessions ({{ $sessions->count() }})
                            </div>
                            @if($sessions->count() > 0)
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Click on session code to copy
                                </small>
                            @endif
                        </div>
                        <a href="{{ route('sessions.create') }}" class="btn-create">
                            <i class="fas fa-plus-circle"></i>
                            Create New Session
                        </a>
                    </div>

                    @if($sessions->count() > 0)
                        <div class="sessions-grid">
                            @foreach($sessions as $session)
                                <div class="session-card animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                    <div class="session-header">
                                        <h6>
                                            <span>{{ $session->name }}</span>
                                            <span class="participant-count">
                                                <i class="fas fa-users"></i>
                                                {{ $session->participants->count() }}
                                            </span>
                                        </h6>
                                    </div>
                                    <div class="session-body">
                                        <div class="mb-3">
                                            <div class="session-code" onclick="copyToClipboard('{{ $session->shareable_link }}', '{{ $session->user->name }}', '{{ $session->name }}')">
                                                {{ $session->code }}
                                                <span class="copy-tooltip">Click to copy link</span>
                                            </div>
                                        </div>

                                        <div class="session-meta">
                                            <span class="status-badge {{ $session->isActive() ? 'status-active' : 'status-inactive' }}">
                                                <i class="fas fa-circle"></i>
                                                {{ $session->isActive() ? 'Active' : 'Inactive' }}
                                            </span>

                                            @if($session->expires_at)
                                            <div class="session-expiry">
                                                <i class="fas fa-clock"></i>
                                                <span>{{ $session->expires_at->diffForHumans() }}</span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="text-center mb-2">
                                            <small class="text-muted d-block mb-1">Scan QR code:</small>
                                            <img src="{{ route('sessions.qr-code', $session) }}" alt="QR Code" style="max-width: 80px; height: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px; cursor: pointer;" onclick="copyToClipboard('{{ $session->shareable_link }}', '{{ $session->user->name }}', '{{ $session->name }}')" title="Click to copy invitation message">
                                        </div>

                                        <p class="text-muted mb-0 small">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Created {{ $session->created_at->format('M j, Y') }}
                                        </p>

                                        <div class="session-actions">
                                            <a href="{{ route('sessions.show', $session) }}" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                View Session
                                            </a>
                                            <a href="{{ $session->shareable_link }}" target="_blank" class="btn-share">
                                                <i class="fas fa-share-alt"></i>
                                                Share
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state animate__animated animate__fadeIn">
                            <div class="empty-state-icon">
                                <i class="fas fa-gifts"></i>
                            </div>
                            <h4>No Secret Santa Sessions Yet</h4>
                            <p>Create your first session to start organizing gift exchanges with friends, family, or colleagues!</p>
                            <a href="{{ route('sessions.create') }}" class="btn-create">
                                <i class="fas fa-magic me-2"></i>
                                Create Your First Session
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

<!-- Toast Notification -->
<div id="toast" class="toast-notification">
    <i class="fas fa-check-circle toast-icon"></i>
    <span id="toast-message">Copied to clipboard!</span>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard function (global scope)
    function copyToClipboard(link, userName, sessionName) {
        const message = `${userName} invites you to join Secret Santa 🎅 of ${sessionName} with Link:
${link}

Merry Christmas,
Tekando 🎄.`;
        console.log('Copying message:', message);
        navigator.clipboard.writeText(message).then(() => {
            console.log('Successfully copied:', message);
            showToast('Invitation message copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            showToast('Failed to copy');
        });
    }

    // Toast notification function (global scope)
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;
        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Create snowflakes
        function createSnowflakes() {
            const container = document.getElementById('snowflakes');
            const count = 30;
            const emojis = ['❄', '❅', '❆'];

            for (let i = 0; i < count; i++) {
                const snowflake = document.createElement('div');
                snowflake.classList.add('snowflake');
                snowflake.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];

                const size = Math.random() * 1 + 0.5;
                const left = Math.random() * 100;
                const duration = Math.random() * 10 + 5;
                const delay = Math.random() * 5;
                const opacity = Math.random() * 0.3 + 0.2;

                snowflake.style.left = `${left}vw`;
                snowflake.style.fontSize = `${size}rem`;
                snowflake.style.animationDuration = `${duration}s`;
                snowflake.style.animationDelay = `${delay}s`;
                snowflake.style.opacity = opacity;

                container.appendChild(snowflake);
            }
        }



        // Add hover effects to session cards
        const sessionCards = document.querySelectorAll('.session-card');
        sessionCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Initialize
        createSnowflakes();

        // Add animation to create button
        const createBtn = document.querySelector('.btn-create');
        if (createBtn) {
            createBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.02)';
            });

            createBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        }

        // Auto-dismiss success message after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.remove();
                }, 300);
            }, 5000);
        }
    });
</script>
@endpush