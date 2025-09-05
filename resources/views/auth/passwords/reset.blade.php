@extends('frontend.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-info text-white text-center py-4">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-lock me-2"></i>
                            {{ __('Reset Password') }}
                        </h4>
                        <p class="mb-0 opacity-75">Enter your new password</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    {{ __('Email Address') }}
                                </label>
                                <input id="email" type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Enter your email address">

                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    {{ __('New Password') }}
                                </label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="new-password"
                                        placeholder="Enter your new password">
                                    <span class="input-group-text bg-white" style="cursor: pointer;" id="togglePassword">
                                        <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                    </span>
                                </div>

                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    {{ __('Confirm New Password') }}
                                </label>
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control form-control-lg"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm your new password">
                                    <span class="input-group-text bg-white" style="cursor: pointer;"
                                        id="toggleConfirmPassword">
                                        <i class="fas fa-eye text-muted" id="toggleConfirmPasswordIcon"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-info btn-lg fw-bold text-white">
                                    <i class="fas fa-save me-2"></i>
                                    {{ __('Reset Password') }}
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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordToggle = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('password');
                const passwordIcon = document.getElementById('togglePasswordIcon');

                passwordToggle.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    passwordIcon.classList.toggle('fa-eye');
                    passwordIcon.classList.toggle('fa-eye-slash');
                });

                const confirmToggle = document.getElementById('toggleConfirmPassword');
                const confirmInput = document.getElementById('password-confirm');
                const confirmIcon = document.getElementById('toggleConfirmPasswordIcon');

                confirmToggle.addEventListener('click', function() {
                    const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmInput.setAttribute('type', type);
                    confirmIcon.classList.toggle('fa-eye');
                    confirmIcon.classList.toggle('fa-eye-slash');
                });
            });
        </script>
    @endpush
@endsection
