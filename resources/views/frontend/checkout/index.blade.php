@extends('frontend.layouts.app')

@section('title', 'Checkout - MyShop')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-3">
                    <i class="bi bi-credit-card"></i> Checkout
                </h1>
            </div>
        </div>

        @if (
            ($cart && isset($cart['items']) && is_array($cart['items']) && count($cart['items']) > 0) ||
                (!empty($isRepayment) && isset($order) && $order->lines && count($order->lines) > 0))
            @if (!empty($isRepayment) && isset($order))
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>You are repaying for Order #{{ $order->id }}.</strong> Please complete your payment below.
                </div>
            @elseif($user && $user->address)
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Welcome back, {{ $user->name }}!</strong> Your billing information has been pre-filled from
                    your profile.
                    You can modify any fields as needed.
                </div>
            @endif

            <form
                action="@if (!empty($isRepayment) && isset($order)) {{ route('checkout.repay.process', $order) }}@else{{ route('checkout.process') }} @endif"
                method="POST" id="checkout-form">
                @csrf
                <div class="row">
                    <!-- Checkout Form -->
                    <div class="col-lg-8 mb-4">
                        <div class="card mb-4">
                            <div class="card-header"
                                style="background-color: var(--primary-dark);color: var(--white) !important;border-top-left-radius: 8px !important;border-top-right-radius: 8px !important;">
                                <h5 class="mb-0">
                                    <i class="bi bi-person"></i> Billing Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="billing_first_name"
                                            name="billing_address[first_name]" required
                                            value="{{ old('billing_address.first_name', $user ? $user->first_name : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="billing_last_name"
                                            name="billing_address[last_name]" required
                                            value="{{ old('billing_address.last_name', $user ? $user->last_name : '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="billing_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="billing_email"
                                        name="billing_address[email]" required
                                        value="{{ old('billing_address.email', $user ? $user->email : '') }}">
                                </div>

                            

                                @php
                                    $countries = App\Models\Country::listCountries();
                                @endphp
                                <div class="mb-3">
                                    <label for="billing_country" class="form-label">Country *</label>
                                    <select class="form-select" id="billing_country" name="billing_address[country]"
                                        required>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $code => $name)
                                            <option value="{{ $name }}"
                                                {{ old('billing_address.country', $user ? $user->country : '') == $name ? 'selected' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- Order Notes -->
                        <div class="card mb-4">
                            <div class="card-header"
                                style="background-color: var(--primary-dark);color: var(--white) !important;border-top-left-radius: 8px !important;border-top-right-radius: 8px !important;">
                                <h5 class="mb-0">
                                    <i class="bi bi-chat-text"></i> Order Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Special Instructions</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header"
                                style="background-color: var(--primary-dark);color: var(--white) !important;border-top-left-radius: 8px !important;border-top-right-radius: 8px !important;">
                                <h5 class="mb-0">
                                    <i class="bi bi-calculator"></i> Order Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Order Items -->
                                @if (!empty($isRepayment) && isset($order) && $order->lines && count($order->lines) > 0)
                                    @foreach ($order->lines as $line)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h6 class="mb-0">{{ $line->product->name }}</h6>
                                                <small class="text-muted">Qty: {{ $line->quantity }}</small>
                                            </div>
                                            <span>£{{ number_format($line->price, 2) }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($cart['items'] as $itemKey => $item)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                                <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                            </div>
                                            <span>£{{ number_format($item['total'], 2) }}</span>
                                        </div>
                                    @endforeach
                                @endif

                                <hr>

                                <!-- Totals -->
                                @if (!empty($isRepayment) && isset($order))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>£{{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>£{{ number_format($cart['subtotal'], 2) }}</span>
                                    </div>
                                @endif

                                @if ($cart['tax'] > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax:</span>
                                        <span>£{{ number_format($cart['tax'], 2) }}</span>
                                    </div>
                                @endif

                                @if ($cart['shipping'] > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span>£{{ number_format($cart['shipping'], 2) }}</span>
                                    </div>
                                @endif

                                @if ($cart['discount'] > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Discount:</span>
                                        <span>-£{{ number_format($cart['discount'], 2) }}</span>
                                    </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    @if (!empty($isRepayment) && isset($order))
                                        <strong class="price fs-5">£{{ number_format($order->total, 2) }}</strong>
                                    @else
                                        <strong class="price fs-5">£{{ number_format($cart['total'], 2) }}</strong>
                                    @endif
                                </div>

                                <!-- Place Order Button -->
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn custom-btn btn-lg" id="place-order-btn">
                                            @if (!empty($isRepayment) && isset($order))
                                                Repay Now
                                            @else
                                                <i class="bi bi-check-circle"></i> Place Order
                                            @endif
                                        </button>
                                    </div>
                                </div>

                                <small class="text-muted text-center d-block mt-2">
                                    By placing your order, you agree to our terms and conditions.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="alert alert-warning text-center my-5">
                <i class="bi bi-cart-x fs-1 mb-3"></i>
                <h4>Your cart is empty</h4>
                <p>Please add items to your cart before proceeding to checkout.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Shop
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')


@endpush
