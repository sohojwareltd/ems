@extends('frontend.layouts.app')

@section('title', 'Order Confirmation - MyShop')

@section('content')
<style>
    /* Page Background */
    body {
        background-color: #f5f7fa;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #00b22d;
        color: white;
        font-weight: 600;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
    }

    .badge-success {
        background-color: #00b22d !important;
    }
    .badge-warning {
        background-color: #ff9800 !important;
    }
    .badge-info {
        background-color: #2196f3 !important;
    }

    .alert-success {
        background-color: #e6f7ee;
        color: #00b22d;
        border: 1px solid #00b22d;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #00b22d;
        border-color: #00b22d;
    }

    .btn-primary:hover {
        background-color: #028a1c;
        border-color: #028a1c;
    }

    .btn-outline-primary {
        color: #00b22d;
        border-color: #00b22d;
    }

    .btn-outline-primary:hover {
        background-color: #00b22d;
        color: white;
        border-color: #00b22d;
    }

    .btn-success {
        background-color: #2196f3;
        border-color: #2196f3;
    }

    .btn-success:hover {
        background-color: #0b7cd1;
        border-color: #0b7cd1;
    }

    .btn-warning {
        background-color: #ff9800;
        border-color: #ff9800;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e68900;
        border-color: #e68900;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-success {
        color: #00b22d !important;
    }

    .bi, .fas {
        color: #00b22d;
    }

    .order-summary .d-flex span {
        font-weight: 500;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                @if (in_array($order->payment_status, ['pending', 'failed']))
                    <h1 class="h2 mb-3 text-warning">Something went wrong!</h1>
                    <p class="lead text-muted">Your order is pending payment. Please make payment to confirm your order.</p>
                    <p class="lead text-muted">Please contact us if you have any questions.</p>
                @else
                    <h1 class="h2 mb-3 text-success">Thank You for Your Order!</h1>
                    <p class="lead text-muted">Your order has been successfully placed and is being processed.</p>
                @endif
                <div class="alert alert-success">
                    <strong>Order Number:</strong> ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <!-- Order Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Order Information</h6>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'success') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            @if ($order->notes)
                                <p><strong>Notes:</strong> {{ $order->notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Payment Information</h6>
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

   
          

       

           

        

        </div>
    </div>
</div>
@endsection
