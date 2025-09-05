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

    @if(
        ($cart && isset($cart['items']) && is_array($cart['items']) && count($cart['items']) > 0) ||
        (!empty($isRepayment) && isset($order) && $order->lines && count($order->lines) > 0)
    )
        @if(!empty($isRepayment) && isset($order))
            <div class="alert alert-warning mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>You are repaying for Order #{{ $order->id }}.</strong> Please complete your payment below.
            </div>
        @elseif($user && $user->address)
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Welcome back, {{ $user->name }}!</strong> Your billing information has been pre-filled from your profile. 
                You can modify any fields as needed.
            </div>
        @endif
        
        <form action="@if(!empty($isRepayment) && isset($order)){{ route('checkout.repay.process', $order) }}@else{{ route('checkout.process') }}@endif" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <!-- Checkout Form -->
                <div class="col-lg-8 mb-4">
                    <!-- Billing Information -->
                    <div class="card mb-4">
                        <div class="card-header">
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
                            
                            <div class="mb-3">
                                <label for="billing_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="billing_phone" 
                                       name="billing_address[phone]" required 
                                       value="{{ old('billing_address.phone', $user ? $user->phone : '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Address *</label>
                                <input type="text" class="form-control" id="billing_address" 
                                       name="billing_address[address]" required 
                                       value="{{ old('billing_address.address', $user ? $user->address : '') }}">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="billing_city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="billing_city" 
                                           name="billing_address[city]" required 
                                           value="{{ old('billing_address.city', $user ? $user->city : '') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="billing_state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="billing_state" 
                                        name="billing_address[state]" required 
                                        value="{{ old('billing_address.state', $user ? $user->state : '') }}">
                                      
                                   
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="billing_zip" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control" id="billing_zip" 
                                           name="billing_address[zip]" required 
                                           value="{{ old('billing_address.zip', $user ? $user->zip : '') }}">
                                </div>
                            </div>
                            
                            @php
                                $countries = App\Models\Country::listCountries();
                            @endphp
                            <div class="mb-3">
                                <label for="billing_country" class="form-label">Country *</label>
                                <select class="form-select" id="billing_country" name="billing_address[country]" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $code => $name)
                                        <option value="{{ $code }}" {{ old('billing_address.country', $user ? $user->country : '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-truck"></i> Shipping Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="same_as_billing" checked>
                                <label class="form-check-label" for="same_as_billing">
                                    Same as billing address
                                </label>
                            </div>
                            
                            <div id="shipping-fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="shipping_first_name" 
                                               name="shipping_address[first_name]" 
                                               value="{{ old('shipping_address.first_name', $user ? $user->first_name : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="shipping_last_name" 
                                               name="shipping_address[last_name]" 
                                               value="{{ old('shipping_address.last_name', $user ? $user->last_name : '') }}">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Address *</label>
                                    <input type="text" class="form-control" id="shipping_address" 
                                           name="shipping_address[address]" 
                                           value="{{ old('shipping_address.address', $user ? $user->address : '') }}">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="shipping_city" 
                                               name="shipping_address[city]" 
                                               value="{{ old('shipping_address.city', $user ? $user->city : '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="shipping_state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="shipping_state" 
                                        name="shipping_address[state]" required 
                                        value="{{ old('shipping_address.state', $user ? $user->state : '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="shipping_zip" class="form-label">ZIP Code *</label>
                                        <input type="text" class="form-control" id="shipping_zip" 
                                               name="shipping_address[zip]" 
                                               value="{{ old('shipping_address.zip', $user ? $user->zip : '') }}">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="shipping_country" class="form-label">Country *</label>
                                    <select class="form-select" id="shipping_country" name="shipping_address[country]">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $code => $name)
                                            <option value="{{ $code }}" {{ old('shipping_address.country', $user ? $user->country : '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-credit-card"></i> Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    @foreach($paymentMethodsArray as $key => $method)
                                        <option value="{{ $key }}"   {{ old('payment_method') == $key ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Stripe Card Element -->
                            <div id="stripe-fields" style="display: none;">
                                <div class="mb-3">
                                    <label for="card-element" class="form-label">Card Information *</label>
                                    <div id="card-element" class="form-control" style="height: 40px; padding: 10px;">
                                        <!-- Stripe Elements will be inserted here -->
                                    </div>
                                    <div id="card-errors" class="invalid-feedback" role="alert"></div>
                                </div>
                                
                                <!-- Hidden input for payment token -->
                                <input type="hidden" id="payment_token" name="payment_token">
                            </div>

                            <!-- PayPal Fields -->
                            <div id="paypal-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> You will be redirected to PayPal to complete your payment securely.
                                </div>
                                <div class="text-center">
                                    <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_100x26.png" 
                                         alt="PayPal" class="mb-2">
                                    <p class="text-muted small">PayPal is a secure payment method that allows you to pay without sharing your financial information.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="card mb-4">
                        <div class="card-header">
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
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-calculator"></i> Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Order Items -->
                            @if(!empty($isRepayment) && isset($order) && $order->lines && count($order->lines) > 0)
                                @foreach($order->lines as $line)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">{{ $line->product->name }}</h6>
                                            <small class="text-muted">Qty: {{ $line->quantity }}</small>
                                        </div>
                                        <span>${{ number_format($line->price, 2) }}</span>
                                    </div>
                                @endforeach
                            @else
                                @foreach($cart['items'] as $itemKey => $item)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                            <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                        </div>
                                        <span>${{ number_format($item['total'], 2) }}</span>
                                    </div>
                                @endforeach
                            @endif
                            
                            <hr>
                            
                            <!-- Totals -->
                            @if(!empty($isRepayment) && isset($order))
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($cart['subtotal'], 2) }}</span>
                                </div>
                            @endif
                            
                            @if($cart['tax'] > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>${{ number_format($cart['tax'], 2) }}</span>
                                </div>
                            @endif
                            
                            @if($cart['shipping'] > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>${{ number_format($cart['shipping'], 2) }}</span>
                                </div>
                            @endif
                            
                            @if($cart['discount'] > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount:</span>
                                    <span>-${{ number_format($cart['discount'], 2) }}</span>
                                </div>
                            @endif
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                @if(!empty($isRepayment) && isset($order))
                                    <strong class="price fs-5">${{ number_format($order->total, 2) }}</strong>
                                @else
                                    <strong class="price fs-5">${{ number_format($cart['total'], 2) }}</strong>
                                @endif
                            </div>

                            <!-- Place Order Button -->
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary btn-lg" id="place-order-btn">
                                        @if(!empty($isRepayment) && isset($order))
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
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config("services.stripe.publishable_key") }}');
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    // Mount card element
    cardElement.mount('#card-element');

    // Handle real-time validation errors
    cardElement.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            displayError.style.display = 'block';
        } else {
            displayError.textContent = '';
            displayError.style.display = 'none';
        }
    });

    // Same as billing address toggle
    $('#same_as_billing').change(function() {
        if (this.checked) {
            $('#shipping-fields').hide();
            // Copy billing address to shipping
            copyBillingToShipping();
        } else {
            $('#shipping-fields').show();
        }
    });

    // Copy billing address to shipping
    function copyBillingToShipping() {
        $('#shipping_first_name').val($('#billing_first_name').val());
        $('#shipping_last_name').val($('#billing_last_name').val());
        $('#shipping_address').val($('#billing_address').val());
        $('#shipping_city').val($('#billing_city').val());
        $('#shipping_state').val($('#billing_state').val());
        $('#shipping_zip').val($('#billing_zip').val());
        $('#shipping_country').val($('#billing_country').val());
    }

    // Payment method toggle
    $('#payment_method').change(function() {
        const method = $(this).val();
        $('#stripe-fields, #paypal-fields').hide();
        
        if (method === 'stripe') {
            $('#stripe-fields').show();
        } else if (method === 'paypal') {
            $('#paypal-fields').show();
        }
    });

    // Form validation and submission
    $('#checkout-form').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#place-order-btn');
        const originalText = submitBtn.html();
        const paymentMethod = $('#payment_method').val();
        
        // Show loading state
        submitBtn.html('<i class="bi bi-hourglass-split"></i> Processing...');
        submitBtn.prop('disabled', true);
        
        // Basic validation
        const requiredFields = [
            'billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone',
            'billing_address', 'billing_city', 'billing_state', 'billing_zip', 'billing_country',
            'payment_method'
        ];
        
        let isValid = true;
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val().trim();
            if (!value) {
                $(`#${field}`).addClass('is-invalid');
                isValid = false;
            } else {
                $(`#${field}`).removeClass('is-invalid');
            }
        });
        
        // Check if shipping is different from billing
        if (!$('#same_as_billing').is(':checked')) {
            const shippingFields = [
                'shipping_first_name', 'shipping_last_name', 'shipping_address',
                'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country',
            ];
            
            shippingFields.forEach(field => {
                const value = $(`#${field}`).val().trim();
                if (!value) {
                    $(`#${field}`).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(`#${field}`).removeClass('is-invalid');
                }
            });
        }
        
        if (!isValid) {
            showToast('Please fill in all required fields', 'warning');
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
            return false;
        }
        
        // If same as billing, copy billing to shipping
        if ($('#same_as_billing').is(':checked')) {
            copyBillingToShipping();
        }

        // Handle payment based on method
        if (paymentMethod === 'stripe') {
            // Create payment method with Stripe
            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: $('#billing_first_name').val() + ' ' + $('#billing_last_name').val(),
                    email: $('#billing_email').val(),
                    phone: $('#billing_phone').val(),
                    address: {
                        line1: $('#billing_address').val(),
                        city: $('#billing_city').val(),
                        state: $('#billing_state').val(),
                        postal_code: $('#billing_zip').val(),
                        country: $('#billing_country').val(),
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Handle error
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    errorElement.style.display = 'block';
                    
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                    showToast('Payment error: ' + result.error.message, 'error');
                } else {
                    // Set payment token and submit form
                    $('#payment_token').val(result.paymentMethod.id);
                    submitForm();
                }
            });
        } else if (paymentMethod === 'paypal') {
            // For PayPal, we don't need a token - just submit the form
            $('#payment_token').val('paypal_payment');
            submitForm();
        } else {
            // For other payment methods
            $('#payment_token').val('test_token');
            submitForm();
        }
    });

    // Submit form function
    function submitForm() {
        const formData = new FormData($('#checkout-form')[0]);
        
        $.ajax({
            url: $('#checkout-form').attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('=== PAYPAL DEBUG START ===');
                console.log('Checkout response (raw):', response);
                console.log('Response type:', typeof response);
                console.log('Response stringified:', JSON.stringify(response));
                
                // Handle case where response might be a string
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                        console.log('Parsed response:', response);
                    } catch (e) {
                        console.error('Failed to parse response as JSON:', e);
                        return;
                    }
                                }
                
                console.log('Response keys:', Object.keys(response));
                console.log('response.success:', response.success);
                console.log('response.redirect_required:', response.redirect_required);
                console.log('response.redirect_url:', response.redirect_url);
                console.log('response.approval_url:', response.approval_url);
                console.log('=== PAYPAL DEBUG END ===');
                
                if (response.success) {
                    console.log('Checkout successful, checking redirect...');
                    console.log('response.redirect_required type:', typeof response.redirect_required);
                    console.log('response.redirect_required value:', response.redirect_required);
                    console.log('response.redirect_required === true:', response.redirect_required === true);
                    
                    if (response.redirect_required === true || response.redirect_required === 'true') {
                        console.log('PAYPAL REDIRECT DETECTED!');
                        // For PayPal, redirect to PayPal approval URL
                        const redirectUrl = response.redirect_url || response.approval_url;
                        console.log('PayPal redirect required, URL:', redirectUrl);
                        
                        if (redirectUrl) {
                            showToast('Redirecting to PayPal...', 'info');
                            console.log('About to redirect to:', redirectUrl);
                            setTimeout(function() {
                                console.log('Executing redirect now...');
                                window.location.href = redirectUrl;
                            }, 1000);
                            return; // Prevent fallthrough to confirmation page
                        } else {
                            console.error('No redirect URL found in response');
                            showToast('Error: No PayPal URL found', 'error');
                            $('#place-order-btn').html('<i class="bi bi-check-circle"></i> Place Order');
                            $('#place-order-btn').prop('disabled', false);
                            return; // Prevent fallthrough to confirmation page
                        }
                    } else {
                        console.log('No redirect required, going to confirmation page');
                        // For other payment methods, redirect to confirmation
                        console.log('Direct confirmation redirect, URL:', response.redirect_url);
                        showToast('Order placed successfully!', 'success');
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1500);
                    }
                } else {
                    console.log('Checkout failed:', response.message);
                    showToast(response.message || 'Order failed. Please try again.', 'error');
                    $('#place-order-btn').html('<i class="bi bi-check-circle"></i> Place Order');
                    $('#place-order-btn').prop('disabled', false);
                }
            },
            error: function(xhr) {
                let message = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
                $('#place-order-btn').html('<i class="bi bi-check-circle"></i> Place Order');
                $('#place-order-btn').prop('disabled', false);
            }
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toastClass = type === 'success' ? 'bg-success' : 
                          type === 'error' ? 'bg-danger' : 
                          type === 'warning' ? 'bg-warning' : 'bg-info';
        
        const toast = `
            <div class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Remove existing toasts
        $('.toast').remove();
        
        // Add new toast
        $('body').append(toast);
        
        // Show toast
        const toastElement = new bootstrap.Toast(document.querySelector('.toast'));
        toastElement.show();
    }

    // Initialize form
    $(document).ready(function() {
        // Copy billing to shipping on page load if user is logged in and has address data
        @if($user && $user->address)
            copyBillingToShipping();
        @endif
        
        // Show/hide payment fields based on selected method
        const selectedMethod = $('#payment_method').val();
        if (selectedMethod === 'stripe') {
            $('#stripe-fields').show();
        } else if (selectedMethod === 'paypal') {
            $('#paypal-fields').show();
        }
        
        // If user is logged in and has address data, show a notification
        @if($user && $user->address)
            showToast('Your billing information has been pre-filled from your profile.', 'info');
        @endif
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(res => res.json())
            .then(response => {a
                if (response.redirect_required && response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else if (response.success) {
                    location.reload();
                } else {
                    console.log(response.message || 'Payment failed.');
                }
            })
            .catch(() => {
                console.log('An error occurred. Please try again.');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
            });
        });
    }
});
</script>
@endpush 