@extends('frontend.layouts.app')

@section('title', 'Claim Access Code')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-ticket-alt me-2" style="color: var(--primary-color)"></i>Claim Access Code
                    </h2>
                    <a href="{{ route('user.subscription') }}" class="btn custom-btn-outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to Subscriptions
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form action="{{ route('user.subscription.claim-access-code.search') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="coupon_code" class="form-label fw-semibold">Access Code</label>
                                <input
                                    type="text"
                                    id="coupon_code"
                                    name="coupon_code"
                                    class="form-control @error('coupon_code') is-invalid @enderror"
                                    value="{{ old('coupon_code', $couponCode ?? '') }}"
                                    placeholder="Enter your access code"
                                    required>
                                @error('coupon_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn custom-btn">
                                <i class="fas fa-search me-2"></i>Get Code
                            </button>
                        </form>
                    </div>
                </div>

                @if (isset($matchedPlan))
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-check-circle me-2 text-success"></i>Matched Plan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                                <div>
                                    <h4 class="mb-1">{{ $matchedPlan->name }}</h4>
                                    <p class="text-muted mb-0">
                                        Duration: Every {{ $matchedPlan->interval_count }} {{ $matchedPlan->interval }}
                                    </p>
                                </div>
                                {{-- <span class="badge bg-success">Coupon Eligible</span> --}}
                            </div>

                            @if ($matchedPlan->description)
                                <div class="mb-3 text-muted">{!! $matchedPlan->description !!}</div>
                            @endif

                            <form action="{{ route('user.subscription.claim-access-code.claim') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $matchedPlan->id }}">
                                <input type="hidden" name="coupon_code" value="{{ $couponCode }}">
                                <button type="submit" class="btn custom-btn">
                                    <i class="fas fa-bolt me-2"></i>Claim Access
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
