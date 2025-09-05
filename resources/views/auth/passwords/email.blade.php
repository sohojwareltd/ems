@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-key me-2"></i>
                        {{ __('Reset Password') }}
                    </h4>
                    <p class="mb-0 opacity-75">Enter your email to receive reset instructions</p>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                {{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" autofocus
                                   placeholder="Enter your email address">

                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0">
                        <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ __('Back to Login') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
