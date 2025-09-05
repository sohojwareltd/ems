@extends('frontend.layouts.app')

@section('title', 'Order Confirmation - MyShop')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    @if (in_array($order->payment_status, ['pending', 'failed']))
                        <h1 class="h2 mb-3">Something went wrong!</h1>
                        <p class="lead text-muted">Your order is pending payment. Please make payment to confirm your order.
                        </p>
                        <p class="lead text-muted">Please contact us if you have any questions.</p>
                    @else
                        <h1 class="h2 mb-3">Thank You for Your Order!</h1>
                        <p class="lead text-muted">Your order has been successfully placed and is being processed.</p>
                    @endif
                    <p class="lead text-muted">Your order has been successfully placed and is being processed.</p>
                    <div class="alert alert-success">
                        <strong>Order Number:</strong> ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt"></i> Order Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Order Information</h6>
                                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                                <p><strong>Status:</strong>
                                    <span
                                        class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'success') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                                @if ($order->notes)
                                    <p><strong>Notes:</strong> {{ $order->notes }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Payment Information</h6>
                                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                <p><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-box"></i> Order Items
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($order->lines as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if ($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product_name }}" class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <small class="text-muted">SKU: {{ $item->sku }}</small>
                                        @if ($item->variant_info)
                                            <br><small class="text-muted">{{ $item->variant_info }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">${{ number_format($item->total, 2) }}</div>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calculator"></i> Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if ($order->tax_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                        @endif

                        @if ($order->shipping_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>${{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                        @endif

                        @if ($order->discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span>-${{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-0">
                            <strong>Total:</strong>
                            <strong class="fs-5">${{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-person"></i> Billing Address
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1">{{ $order->billing_address['first_name'] }}
                                    {{ $order->billing_address['last_name'] }}</p>
                                <p class="mb-1">{{ $order->billing_address['email'] }}</p>
                                <p class="mb-1">{{ $order->billing_address['phone'] }}</p>
                                <p class="mb-1">{{ $order->billing_address['address'] }}</p>
                                <p class="mb-0">{{ $order->billing_address['city'] }},
                                    {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}</p>
                                <p class="mb-0">{{ App\Models\Country::where('code', $order->billing_address['country'])->first()->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-truck"></i> Shipping Address
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1">{{ $order->shipping_address['first_name'] }}
                                    {{ $order->shipping_address['last_name'] }}</p>
                                <p class="mb-1">{{ $order->shipping_address['address'] }}</p>
                                <p class="mb-0">{{ $order->shipping_address['city'] }},
                                    {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}</p>
                                <p class="mb-0">{{ App\Models\Country::where('code', $order->shipping_address['country'])->first()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="{{ route('checkout.order-details', $order->id) }}" class="btn btn-primary me-2">
                        <i class="bi bi-eye"></i> View Order Details
                    </a>
                    <a href="{{ route('checkout.download-invoice', $order->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download Invoice
                    </a>
                    @if (in_array($order->payment_status, ['pending', 'failed']))
                        <a href="{{ route('checkout.repay', $order) }}" class="btn btn-warning">
                            <i class="fas fa-credit-card me-2"></i>Repay
                        </a>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn btn-success">
                        <i class="bi bi-shop"></i> Continue Shopping
                    </a>
                </div>

                <!-- Next Steps -->
                <div class="mt-5">
                    <h5>What's Next?</h5>
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-envelope text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Confirmation Email</h6>
                            <small class="text-muted">You'll receive a confirmation email shortly.</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-truck text-info" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Shipping Updates</h6>
                            <small class="text-muted">We'll notify you when your order ships.</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-headset text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Need Help?</h6>
                            <small class="text-muted">Contact our support team if you have questions.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
