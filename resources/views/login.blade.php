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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
