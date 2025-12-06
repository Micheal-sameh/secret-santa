@extends('layouts.app')

@section('title', $session->name . ' - Secret Santa')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .session-code {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1.1rem;
        background: rgba(196, 30, 58, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border-left: 3px solid var(--santa-red);
    }

    .info-section {
        background: rgba(255, 255, 255, 0.7);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--santa-red);
    }

    .info-section h5 {
        color: var(--santa-dark);
        font-weight: 600;
        margin-bottom: 1rem;
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

    .copy-btn {
        border-radius: 0 8px 8px 0;
    }

    .share-input {
        border-radius: 8px 0 0 8px;
    }
</style>
@endpush

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><span class="gift-icon"></span>{{ $session->name }}</h3>
                        <p class="mb-0 mt-2">Secret Santa Session</p>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="info-section">
                                    <h5><i class="fas fa-info-circle me-2"></i>Session Details</h5>
                                    <div class="mb-3">
                                        <strong>Session Code:</strong>
                                        <div class="session-code mt-2">{{ $session->code }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        @if($session->isActive())
                                            <span class="badge bg-success ms-2">Active</span>
                                        @else
                                            <span class="badge bg-danger ms-2">Inactive</span>
                                        @endif
                                    </div>
                                    @if($session->expires_at)
                                        <div class="mb-2">
                                            <strong>Expires:</strong>
                                            <span class="text-muted">{{ $session->expires_at->format('M j, Y g:i A') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="info-section">
                                    <h5><i class="fas fa-share-alt me-2"></i>Share Session</h5>
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control share-input" id="shareLink" value="{{ $session->shareable_link }}" readonly data-user-name="{{ $session->user->name }}" data-session-name="{{ $session->name }}">
                                            <button class="btn btn-outline-secondary copy-btn" type="button" id="copyButton" onclick="copyToClipboard()">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-0">
                                        Share this link to invite others to your Secret Santa
                                    </p>
                                    <div class="text-center mt-3">
                                        <small class="text-muted d-block mb-2">Or scan this QR code:</small>
                                        <img src="{{ route('sessions.qr-code', $session) }}" alt="QR Code" style="max-width: 150px; height: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h5><i class="fas fa-users me-2"></i>Participants ({{ $session->participants->count() }})</h5>
                            @if($session->participants->count() > 0)
                                <div class="row">
                                    @foreach($session->participants as $participant)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $participant->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">Joined {{ $participant->created_at->format('M j, Y') }}</small>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" title="Remove participant" onclick="openDeleteModal({{ $participant->id }}, '{{ $participant->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">No participants have joined this session yet.</p>
                            @endif
                        </div>

                        <div class="d-flex gap-3 mt-3 flex-wrap">
                            <a href="{{ route('sessions.secret-santa', $session) }}" class="btn btn-primary">
                                <i class="fas fa-gift me-2"></i>Draw Secret Santa
                            </a>
                            <a href="{{ route('sessions.check-assignment', $session) }}" class="btn btn-success">
                                <i class="fas fa-search me-2"></i>Check My Drawing
                            </a>
                            <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Sessions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Are you sure you want to remove <strong id="participantName"></strong> from this Secret Santa session?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Remove Participant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

        function copyToClipboard() {
            const shareLink = document.getElementById('shareLink');
            shareLink.select();
            shareLink.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(shareLink.value).then(function() {
                const button = document.getElementById('copyButton');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-success');

                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                document.execCommand('copy');
            });
        }

        function openDeleteModal(participantId, participantName) {
            document.getElementById('participantName').textContent = participantName;
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = '{{ route("sessions.participants.destroy", [$session, ":participantId"]) }}'.replace(':participantId', participantId);

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

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

    function copyToClipboard() {
        const shareLink = document.getElementById('shareLink');
        const userName = shareLink.getAttribute('data-user-name');
        const sessionName = shareLink.getAttribute('data-session-name');
        const link = shareLink.value;

        const message = `${userName} invites you to join Secret Santa 🎅 of ${sessionName} with Link:
${link}

Merry Christmas,
Tekando🎄.`;

        navigator.clipboard.writeText(message).then(function() {
            const button = document.getElementById('copyButton');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            // Fallback for older browsers
            shareLink.value = message;
            shareLink.select();
            shareLink.setSelectionRange(0, 99999);
            document.execCommand('copy');
            shareLink.value = link; // Reset to original value
        });
    }

    function openDeleteModal(participantId, participantName) {
        document.getElementById('participantName').textContent = participantName;
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '{{ route("sessions.participants.destroy", [$session, ":participantId"]) }}'.replace(':participantId', participantId);

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', createSnowflakes);
</script>
@endpush
