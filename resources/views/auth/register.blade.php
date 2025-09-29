@extends('frontend.layouts.app')

@section('title', 'Register - EMS')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus " style="font-size: 2rem;color:var(--primary-color);"></i>
                    </div>
                    <h2 class="fw-bold  mb-2" style="color:var(--primary-color);">Join Our Community</h2>
                    <p class="text-muted mb-0">Create your account and start learning today</p>
                </div>

                <!-- Register Form Card -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name and Email Row -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="First Name" required autocomplete="name" autofocus>
                                        <label for="name">
                                            <i class="fas fa-user me-2 text-muted"></i>
                                            First Name
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                            id="lastname" name="lastname" value="{{ old('lastname') }}"
                                            placeholder="Last Name" required autocomplete="lastname">
                                        <label for="lastname">
                                            <i class="fas fa-user me-2 text-muted"></i>
                                            Last Name </label>
                                        @error('lastname')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Email Address" required autocomplete="email">
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
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="form-floating">
                                    <input type="email"
                                        class="form-control @error('email_confirmation') is-invalid @enderror"
                                        id="email_confirmation" name="email_confirmation"
                                        value="{{ old('email_confirmation') }}" placeholder="Confirm Email Address" required
                                        autocomplete="email">
                                    <label for="email_confirmation">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        Confirm Email Address
                                    </label>
                                    @error('email_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <!-- Password Row -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="birthdate" class="form-label">
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                        Date of Birth
                                    </label>
                                    <input type="text"
                                        class="form-control flatpickr-dob @error('birthdate') is-invalid @enderror"
                                        id="birthdate" name="birthdate" value="{{ old('birthdate') }}"
                                        placeholder="dd/mm/yyyy" required>
                                    @error('birthdate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6 mb-4">
                                    <label for="stage">
                                        <i class="fas fa-graduation-cap me-2 text-muted"></i>
                                        Stage
                                    </label>
                                    <select name="stage" id="stage"
                                        class="form-select @error('stage') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select your stage</option>
                                        <option value="KS3" {{ old('stage') == 'KS3' ? 'selected' : '' }}>KS3</option>
                                        <option value="GCSE" {{ old('stage') == 'GCSE' ? 'selected' : '' }}>GCSE</option>
                                        <option value="A Levels" {{ old('stage') == 'A Levels' ? 'selected' : '' }}>A
                                            Levels</option>
                                        @error('stage')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </select>
                                </div> --}}

                                @php
                                    $Country = App\Models\Country::all()->pluck('name')->toArray();
                                @endphp
                                <div class="col-md-6 mb-4">
                                    <label for="country" class="form-label">
                                        <i class="fas fa-globe me-2 text-muted"></i>
                                        Country
                                    </label>
                                    <select name="country" id="country"
                                        class="form-select @error('country') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select your country</option>
                                        @foreach ($Country as $country)
                                            <option value="{{ $country }}"
                                                {{ old('country') == $country ? 'selected' : '' }}>
                                                {{ $country }}</option>
                                        @endforeach
                                        @error('country')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating">
                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" placeholder="Password" required autocomplete="new-password">
                                            <label for="password">
                                                <i class="fas fa-lock me-2 text-muted"></i>
                                                Password
                                            </label>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50  pe-3"
                                                id="togglePassword" style="transform: translateY(-78%) !important;">
                                                <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2" id="password-strength"></div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating">
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="password-confirm"
                                                name="password_confirmation" placeholder="Confirm Password" required
                                                autocomplete="new-password">
                                            <label for="password-confirm">
                                                <i class="fas fa-lock me-2 text-muted"></i>
                                                Confirm Password
                                            </label>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50  pe-3"
                                                id="togglePasswordConfirm"
                                                style="transform: translateY(-78%) !important;">
                                                <i class="fas fa-eye text-muted" id="togglePasswordConfirmIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms"
                                        required>
                                    <label class="form-check-label text-muted" for="terms">
                                        I agree to the
                                        <a href="#" class="text-decoration-none">
                                            <span class="text-primary fw-semibold">Terms of Service</span>
                                        </a>
                                        and
                                        <a href="#" class="text-decoration-none">
                                            <span class="text-primary fw-semibold">Privacy Policy</span>
                                        </a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn custom-btn btn-lg fw-semibold py-3 rounded-3">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Create Account
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

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                                    Sign in here
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
            // Show/hide password toggle
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

            document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
                const passwordInput = document.getElementById('password-confirm');
                const icon = document.getElementById('togglePasswordConfirmIcon');

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

            // Password strength indicator
            document.getElementById('password').addEventListener('input', function() {
                const val = this.value;
                const strength = document.getElementById('password-strength');
                let score = 0;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                let msg = '',
                    color = '';
                switch (score) {
                    case 0:
                    case 1:
                        msg = 'Weak';
                        color = 'danger';
                        break;
                    case 2:
                        msg = 'Fair';
                        color = 'warning';
                        break;
                    case 3:
                        msg = 'Good';
                        color = 'info';
                        break;
                    case 4:
                        msg = 'Strong';
                        color = 'success';
                        break;
                }

                if (val.length === 0) {
                    strength.innerHTML = '';
                } else {
                    strength.innerHTML = `<span class='badge bg-${color} fs-6'>${msg}</span>`;
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

        .badge {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.75rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem !important;
            }
        }
    </style>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr(".flatpickr-dob", {
            dateFormat: "d/m/Y", // dd/mm/yyyy format
            maxDate: "today", // Prevent future dates
            defaultDate: "01/01/2000", // Optional starting point
            altInput: true,
            altFormat: "F j, Y",
            disableMobile: "true", // Always use flatpickr even on mobile
        });
    </script>


@endsection
