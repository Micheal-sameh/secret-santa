@extends('layouts.app')

@section('title', 'Secret Santa Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card  mt-3">
                <div class="card-header position-relative">
                    <div class="santa-hat"></div>
                    <h3><span class="gift-icon"></span>Secret Santa Login</h3>
                    <p class="mb-0 mt-2">Welcome back to the gift exchange!</p>
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

                    <!-- Google Sign In Button -->
                    <div class="mb-3">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                            <svg width="20" height="20" class="me-2" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Sign in with Google
                        </a>
                    </div>

                    <div class="text-center mb-3">
                        <span class="text-muted">or</span>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" required placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                placeholder="Enter your password">
                        </div>

                        <button type="submit" class="btn btn-primary">Unwrap Your Gifts</button>
                    </form>

                    <div class="footer-text">
                        <p>Don't have an account? <a href="{{ route('register') }}">Join the Secret Santa fun!</a>
                        </p>
                        <p><a href="{{ route('password.request') }}">Forgot Password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
