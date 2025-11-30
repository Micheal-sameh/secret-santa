@extends('layouts.app')

@section('title', 'Secret Santa Registration')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card  mt-3">
                <div class="card-header position-relative">
                    <div class="santa-hat"></div>
                    <h3><span class="gift-icon"></span>Secret Santa Registration</h3>
                    <p class="mb-0 mt-2">Join the festive gift exchange!</p>
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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required placeholder="Enter your full name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" required placeholder="Enter your email address">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                placeholder="Create a secure password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required placeholder="Re-enter your password">
                        </div>

                        <button type="submit" class="btn btn-primary">Join Secret Santa</button>
                    </form>

                    <div class="footer-text">
                        Already have an account? <a href="{{ route('login') }}" style="color: var(--santa-gold);">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
