@extends('frontend.layouts.app')

@section('title', 'Edit Profile - MyShop')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 fw-bold " style="color: var(--primary-color) !important;">
                            <i class="fas fa-user-edit me-2 " style="color: var(--primary-color) !important;"></i>
                            Edit Profile
                        </h1>
                        <p class="text-muted mb-0">Update your personal information and account settings</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn custom-btn-outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header  border-0 py-3" style="background-color: var(--primary-dark);color:var(--white) !important;">
                        <h5 class="mb-0 fw-bold ">
                            <i class="fas fa-user me-2 "></i>
                            Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                        id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}"
                                        required>
                                    @error('lastname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" readonly value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold">WhatsApp Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="billing_country" class="form-label">Country *</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Select Country</option>
                                        @foreach (App\Models\Country::all() as $country)
                                            <option value="{{ $country->code }}"
                                                {{ old('country', $user ? $user->country : '') == $country->code ? 'selected' : '' }}>
                                                {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label fw-semibold">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', $user->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label fw-semibold">State/Province</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                        id="state" name="state" value="{{ old('state', $user->state) }}">   
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label fw-semibold">ZIP/Postal Code</label>
                                    <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                        id="zip" name="zip" value="{{ old('zip', $user->zip) }}">
                                    @error('zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn custom-btn">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background-color: var(--primary-dark);color:var(--white) !important;">
                        <h5 class="mb-0 fw-bold ">
                            <i class="fas fa-lock me-2 "></i>
                            Change Password
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.password.change') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm New
                                    Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn custom-btn">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header  border-0 py-3" style="background-color: var(--primary-dark);color:var(--white) !important;">
                        <h5 class="mb-0 fw-bold ">
                            <i class="fas fa-info-circle me-2 "></i>
                            Account Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Member Since</label>
                            <p class="mb-0 fw-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Email Verified</label>
                            <p class="mb-0">
                                @if ($user->email_verified_at)
                                    <span class="badge custom-badge">
                                        <i class="fas fa-check me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge custom-badge">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Role</label>
                            <p class="mb-0 fw-semibold">{{ $user->role->name ?? 'Customer' }}</p>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('dashboard') }}" class="btn custom-btn-outline">
                                <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .form-label {
            color: #374151;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection
