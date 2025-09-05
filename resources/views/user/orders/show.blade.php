@extends('frontend.layouts.app')

@section('title', 'Order #' . $order->id . ' - MyShop')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1 fw-bold text-dark">
                        <i class="fas fa-receipt me-2 text-primary"></i>
                        Order #{{ $order->id }}
                    </h1>
                    <p class="text-muted mb-0">Ordered on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    <a href="{{ route('checkout.download-invoice', $order->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download Invoice
                    </a>
                        @if(in_array($order->payment_status, ['pending', 'failed']))
                            <a href="{{ route('checkout.repay', $order) }}" class="btn btn-warning">
                                <i class="fas fa-credit-card me-2"></i>Repay
                            </a>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8 mb-4">
            <!-- Order Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Order Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $order->status_color }} fs-6 me-3">{{ ucfirst($order->status) }}</span>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Order Status</h6>
                                    <p class="text-muted mb-0">Last updated: {{ $order->updated_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($order->tracking)
                                <div class="mb-2">
                                    <strong>Tracking Number:</strong> {{ $order->tracking }}
                                </div>
                                <div>
                                    <strong>Shipping Method:</strong> {{ $order->shipping_method ?? 'Standard Shipping' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-shopping-bag me-2 text-primary"></i>
                        Order Items
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->lines as $line)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            @if($line->product && $line->product->image)
                                <img src="{{ asset('storage/' . $line->product->image) }}" 
                                     alt="{{ $line->product->name }}" 
                                     class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-box text-muted fs-4"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">{{ $line->product->name ?? 'Product' }}</h6>
                            <p class="text-muted mb-1">SKU: {{ $line->product->sku ?? 'N/A' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Qty: {{ $line->quantity }}</span>
                                    <span class="text-muted ms-3">Price: ${{ number_format($line->price, 2) }}</span>
                                </div>
                                <div class="fw-semibold">${{ number_format($line->price * $line->quantity, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping & Billing Address -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-shipping-fast me-2 text-primary"></i>
                                Shipping Address
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($order->shipping_address)
                                <p class="mb-1 fw-semibold">{{ $order->shipping_address['name'] ?? '' }}</p>
                                <p class="mb-1">{{ $order->shipping_address['address'] ?? '' }}</p>
                                <p class="mb-1">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}</p>
                                <p class="mb-1">{{ App\Models\Country::where('code', $order->shipping_address['country'])->first()->name ?? '' }}</p>
                                <p class="mb-0">{{ $order->shipping_address['phone'] ?? '' }}</p>
                            @else
                                <p class="text-muted mb-0">No shipping address provided</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                Billing Address
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($order->billing_address)
                                <p class="mb-1 fw-semibold">{{ $order->billing_address['name'] ?? '' }}</p>
                                <p class="mb-1">{{ $order->billing_address['address'] ?? '' }}</p>
                                <p class="mb-1">{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip'] ?? '' }}</p>
                                <p class="mb-1">{{ App\Models\Country::where('code', $order->billing_address['country'])->first()->name ?? '' }}</p>
                                <p class="mb-0">{{ $order->billing_address['phone'] ?? '' }}</p>
                            @else
                                <p class="text-muted mb-0">No billing address provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-calculator me-2 text-primary"></i>
                        Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Discount:</span>
                        <span class="text-success">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax:</span>
                        <span>${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->shipping_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping:</span>
                        <span>${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-semibold">Total:</span>
                        <span class="fw-bold fs-5">${{ number_format($order->total, 2) }}</span>
                    </div>

                    @if($order->coupon_code)
                    <div class="alert alert-info">
                        <i class="fas fa-tag me-2"></i>
                        Coupon applied: <strong>{{ $order->coupon_code }}</strong>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <p class="mb-0">{{ ucfirst($order->payment_method ?? 'Not specified') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Status</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </p>
                    </div>

                    @if($order->notes)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Order Notes</label>
                        <p class="mb-0 text-muted">{{ $order->notes }}</p>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.download-invoice', $order->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Download Invoice
                        </a>
                        @if($order->tracking)
                        <a href="#" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#trackingModal">
                            <i class="fas fa-truck me-2"></i>Track Package
                        </a>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Order History / Event Log as Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        Order History
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="timeline list-unstyled mb-0">
                        @foreach($order->histories()->latest()->get() as $history)
                        <li class="timeline-item position-relative mb-4 pb-2">
                            <span class="timeline-marker position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width:14px; height:14px;"></span>
                            <div class="ms-4">
                                <div class="d-flex align-items-center mb-1">
                                    @php
                                        $icon = match($history->event) {
                                            'created' => 'fas fa-plus-circle text-success',
                                            'status_changed' => 'fas fa-exchange-alt text-info',
                                            'shipping_method_changed' => 'fas fa-truck text-warning',
                                            default => 'fas fa-info-circle text-secondary',
                                        };
                                    @endphp
                                    <i class="{{ $icon }} me-2"></i>
                                    <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $history->event)) }}</span>
                                    <span class="text-muted small ms-2">{{ $history->created_at->format('M d, Y g:i A') }}</span>
                                </div>
                                <div class="text-muted small">{{ $history->description }}</div>
                                @if($history->event === 'status_changed')
                                    <div class="small mt-1">
                                        <span class="badge bg-light text-dark">From: <strong>{{ $history->old_value['status'] ?? '' }}</strong></span>
                                        <span class="badge bg-primary ms-2">To: <strong>{{ $history->new_value['status'] ?? '' }}</strong></span>
                                    </div>
                                @elseif($history->event === 'shipping_method_changed')
                                    <div class="small mt-1">
                                        <span class="badge bg-light text-dark">From: <strong>{{ $history->old_value['shipping_method'] ?? '' }}</strong></span>
                                        <span class="badge bg-warning ms-2">To: <strong>{{ $history->new_value['shipping_method'] ?? '' }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
@if($order->tracking)
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck me-2 text-primary"></i>
                    Track Order #{{ $order->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tracking Number</label>
                    <p class="mb-0">{{ $order->tracking }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Shipping Method</label>
                    <p class="mb-0">{{ $order->shipping_method ?? 'Standard Shipping' }}</p>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Track your package using the tracking number above on the carrier's website.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e2e8f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e2e8f0;
}

.timeline-content h6 {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.timeline-content p {
    font-size: 0.75rem;
}
</style>
@endsection 