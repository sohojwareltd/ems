@extends('frontend.layouts.app')

@section('title', 'Login - MyShop')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-shopping-bag text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">Welcome Back</h2>
                    <p class="text-muted mb-0">Sign in to continue your learning journey</p>
                </div>

                <!-- Login Form Card -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-md-5">
                        @if (session('status'))
                            <div class="alert alert-success border-0 rounded-3 mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="form-floating mb-4">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com"
                                    required autocomplete="email" autofocus>
                                <label for="email">
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    Email Address
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="form-floating mb-4">
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password" required
                                        autocomplete="current-password">
                                    <label for="password">
                                        <i class="fas fa-lock me-2 text-muted"></i>
                                        Password
                                    </label>
                                    <button type="button"
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3"
                                        style="margin-bottom: 5px;padding-bottom: 25px;" id="togglePassword">
                                        <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mb-3">

                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        <small class="text-primary fw-semibold">Forgot password?</small>
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn custom-btn btn-lg fw-semibold py-3 rounded-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="text-center mb-4">
                            <div class="position-relative">
                                <hr class="text-muted">
                                <span
                                    class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                                    Sign up here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = document.getElementById('togglePasswordIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        </script>
    @endpush

    <style>
        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            color: #6c757d;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-link {
            text-decoration: none;
        }

        .btn-link:hover {
            color: var(--primary-color) !important;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem !important;
            }
        }
    </style>

    <script>
        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        function generateUUID() {
            return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }

        if (!getCookie('device_id')) {
            setCookie('device_id', generateUUID(), 365);
        }
    </script>

@endsection
