@extends('layouts.app')

@section('content')
    <style>
        body {
            overflow-y: auto;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3><span class="gift-icon"></span>Secret Santa Assignments</h3>
                        <p class="mb-0 mt-2">{{ $session->name }}</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="secret-reminder">
                            <h5 class="mb-2" style="color: var(--santa-red);"><i class="fas fa-gift me-2"></i>Secret Santa
                                Rules</h5>
                            <ul class="mb-0">
                                <li>Each participant has been randomly assigned someone to buy a gift for</li>
                                <li>Keep your assignment secret until the gift exchange!</li>
                                <li>The assignments ensure no one gets themselves</li>
                            </ul>
                        </div>

                        <div class="row">
                            @foreach ($assignments as $assignment)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="assignment-card">
                                        <div class="assignment-header">
                                            <div class="giver-name">{{ $assignment['giver']['name'] }}</div>
                                        </div>
                                        <div class="assignment-body">
                                            <div class="mb-2">
                                                <strong style="color: var(--santa-dark);">Buys for:</strong>
                                                <div class="recipient-name">{{ $assignment['recipient']['name'] }}</div>
                                            </div>
                                            <div class="alert alert-warning" style="padding: 0.5rem; margin-bottom: 0;">
                                                <small><i class="fas fa-eye-slash me-1"></i>Keep this secret!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex gap-3 mt-4 flex-wrap justify-content-center">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print me-2"></i>Print Assignments
                            </button>
                            <a href="{{ route('sessions.secret-santa.export-pdf', $session) }}" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                            <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Session
                            </a>
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>All Sessions
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
