<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Santa - {{ $session->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Secret Santa Assignments - {{ $session->name }}</h3>
                        <a href="{{ route('sessions.show', $session) }}" class="btn btn-secondary">Back to Session</a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <strong>Secret Santa Rules:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Each participant has been randomly assigned someone to buy a gift for</li>
                                <li>Keep your assignment secret until the gift exchange!</li>
                                <li>The assignments ensure no one gets themselves</li>
                            </ul>
                        </div>

                        <div class="row">
                            @foreach($assignments as $assignment)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $assignment['giver']['name'] }}</h5>
                                            <p class="card-text">
                                                <strong>Buys for:</strong> {{ $assignment['recipient']['name'] }}
                                            </p>
                                            <div class="alert alert-warning">
                                                <small>Remember to keep this assignment secret!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print"></i> Print Assignments
                            </button>
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary ms-2">
                                Back to Sessions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
