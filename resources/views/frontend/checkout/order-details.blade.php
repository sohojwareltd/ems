@extends('frontend.layouts.app')

@section('title', 'Order Details - MyShop')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-2">
                            <i class="bi bi-receipt"></i> Order Details
                        </h1>
                        <p class="text-muted mb-0">Order #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-end">
                        @if (in_array($order->payment_status, ['pending', 'failed']))
                            <a href="{{ route('checkout.repay', $order) }}" class="btn btn-warning">
                                <i class="fas fa-credit-card me-2"></i>Repay
                            </a>
                        @endif
                        <a href="{{ route('checkout.download-invoice', $order->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Download Invoice
                        </a>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-2">Order Status</h5>
                                <span
                                    class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : 'success')) }} fs-6">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1"><strong>Order Date:</strong>
                                    {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                                <p class="mb-0"><strong>Last Updated:</strong>
                                    {{ $order->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Order Items -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-box"></i> Order Items
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach ($order->lines as $item)
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        alt="{{ $item->product_name }}" class="img-thumbnail"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 80px; height: 80px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                <p class="text-muted mb-1">SKU: {{ $item->sku }}</p>
                                                @if ($item->variant_info)
                                                    <p class="text-muted mb-1">{{ $item->variant_info }}</p>
                                                @endif
                                                <p class="text-muted mb-0">Quantity: {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold fs-5">${{ number_format($item->total, 2) }}</div>
                                            <small class="text-muted">${{ number_format($item->price, 2) }} each</small>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        @if ($order->shipping_method || $order->tracking_number)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-truck"></i> Shipping Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($order->shipping_method)
                                        <p><strong>Shipping Method:</strong> {{ $order->shipping_method }}</p>
                                    @endif
                                    @if ($order->tracking_number)
                                        <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                                    @endif
                                    @if ($order->shipped_at)
                                        <p><strong>Shipped Date:</strong> {{ $order->shipped_at->format('F j, Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Order Notes -->
                        @if ($order->notes)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-chat-text"></i> Order Notes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $order->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
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

                        <!-- Payment Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-credit-card"></i> Payment Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                <p><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Addresses -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-geo-alt"></i> Addresses
                                </h5>
                            </div>
                            <div class="card-body">
                                <h6>Billing Address</h6>
                                <p class="mb-2">
                                    {{ $order->billing_address['first_name'] }}
                                    {{ $order->billing_address['last_name'] }}<br>
                                    {{ $order->billing_address['email'] }}<br>
                                    {{ $order->billing_address['phone'] }}<br>
                                    {{ $order->billing_address['address'] }}<br>
                                    {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }}
                                    {{ $order->billing_address['zip'] }}<br>
                                    {{ App\Models\Country::where('code', $order->billing_address['country'])->first()->name }}
                                </p>

                                <hr>

                                <h6>Shipping Address</h6>
                                <p class="mb-0">
                                    {{ $order->shipping_address['first_name'] }}
                                    {{ $order->shipping_address['last_name'] }}<br>
                                    {{ $order->shipping_address['address'] }}<br>
                                    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}
                                    {{ $order->shipping_address['zip'] }}<br>
                                    {{ App\Models\Country::where('code', $order->shipping_address['country'])->first()->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="{{ route('products.index') }}" class="btn btn-primary me-2">
                        <i class="bi bi-shop"></i> Continue Shopping
                    </a>
                    <a href="{{ route('checkout.confirmation', $order->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Confirmation
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
