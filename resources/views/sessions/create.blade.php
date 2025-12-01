@extends('layouts.app')

@section('title', 'Create Secret Santa')

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .form-text {
        color: #6c757d;
        font-style: italic;
        margin-top: 0.25rem;
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
        0%, 100% {
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
@endpush

@section('content')
<!-- Floating Gifts -->
<div class="floating-gift gift-1"></div>
<div class="floating-gift gift-2"></div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
@endsection

@push('scripts')
<script>
    // Set default expiration to one week from now
    document.addEventListener('DOMContentLoaded', function() {
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
@endpush
