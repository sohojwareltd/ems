@extends('frontend.layouts.app')

@section('title', 'Dashboard - MyShop')

@section('content')
    <div class="container py-5">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 fw-bold" style="color: var(--primary-color);">
                            <i class="fas fa-tachometer-alt me-2 " ></i>
                            Dashboard
                        </h1>
                        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.profile') }}" class="btn custom-btn-outline">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('user.orders.index') }}" class="btn custom-btn">
                            <i class="fas fa-box-open me-2"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="d-inline-flex align-items-center justify-content-center custom-badge rounded-circle mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-shopping-bag  fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">{{ $stats['total_orders'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="d-inline-flex align-items-center justify-content-center custom-badge rounded-circle mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle  fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">{{ $stats['completed_orders'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="d-inline-flex align-items-center justify-content-center custom-badge rounded-circle mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-clock  fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">{{ $stats['pending_orders'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="d-inline-flex align-items-center justify-content-center custom-badge rounded-circle mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-pound-sign  fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">£{{ number_format($stats['total_spent'] ?? 0, 2) }}</h3>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: var(--primary-color);">
                                <i class="fas fa-history me-2 "></i>
                                Recent Orders
                            </h5>
                            <a href="{{ route('user.orders.index') }}" class="btn btn-sm custom-btn-outline">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($recent_orders) && $recent_orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Order #</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Total</th>
                                            <th class="border-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_orders as $order)
                                            <tr>
                                                <td class="align-middle">
                                                    <span class="fw-semibold">#{{ $order->id }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="align-middle">
                                                    <span
                                                        class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td class="align-middle fw-semibold">
                                                    £{{ number_format($order->total, 2) }}
                                                </td>
                                                <td class="align-middle">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('user.orders.show', $order) }}"
                                                            class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('orders.print-invoice', $order) }}"
                                                            class="btn btn-outline-secondary btn-sm" target="_blank">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fs-1 mb-3" style="color: var(--primary-color);"></i>
                                <h5 class="" style="color: var(--primary-color);">No orders yet</h5>
                                <p class="text-muted">Start shopping to see your orders here!</p>
                                <a href="{{ route('products.index') }}" class="btn custom-btn">
                                    <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Profile Summary -->
            <div class="col-lg-4 mb-4">
                <!-- Profile Summary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold " style="color: var(--primary-color);">
                            <i class="fas fa-user me-2"></i>
                            Profile Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="custom-badge rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 fw-semibold">{{ Auth::user()->name }}</h6>
                                <p class="text-muted mb-0 small">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="fw-bold text-dark mb-1">{{ Auth::user()->created_at->format('M Y') }}</h6>
                                    <p class="text-muted mb-0 small">Member Since</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="fw-bold text-dark mb-1">{{ $stats['total_orders'] ?? 0 }}</h6>
                                <p class="text-muted mb-0 small">Orders</p>
                            </div>
                        </div>
                    </div>
                </div>
       

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-bolt me-2 text-primary"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.profile') }}" class="btn custom-btn-outline text-start">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                            <a href="{{ route('user.orders.index') }}" class="btn btn-outline-success text-start">
                                <i class="fas fa-box-open me-2"></i>My Orders
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-info text-start">
                                <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                            </a>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-warning text-start">
                                <i class="fas fa-shopping-bag me-2"></i>View Cart
                            </a>
                            <a href="{{ route('user.subscription') }}" class="btn btn-outline-dark text-start">
                                <i class="fas fa-shopping-bag me-2"></i>Subscriptions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        @if (isset($stats) && $stats['total_orders'] > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>
                                Order Status Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-2"
                                        style="width: 80px; height: 80px;">
                                        <span
                                            class="fw-bold text-success fs-4">{{ $stats['completed_orders'] ?? 0 }}</span>
                                    </div>
                                    <h6 class="fw-semibold">Completed</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                                    </p>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-2"
                                        style="width: 80px; height: 80px;">
                                        <span class="fw-bold text-warning fs-4">{{ $stats['pending_orders'] ?? 0 }}</span>
                                    </div>
                                    <h6 class="fw-semibold">Pending</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $stats['total_orders'] > 0 ? round(($stats['pending_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                                    </p>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-2"
                                        style="width: 80px; height: 80px;">
                                        <span class="fw-bold text-info fs-4">{{ $stats['processing_orders'] ?? 0 }}</span>
                                    </div>
                                    <h6 class="fw-semibold">Processing</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $stats['total_orders'] > 0 ? round(($stats['processing_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                                    </p>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle mb-2"
                                        style="width: 80px; height: 80px;">
                                        <span
                                            class="fw-bold text-danger fs-4">{{ $stats['cancelled_orders'] ?? 0 }}</span>
                                    </div>
                                    <h6 class="fw-semibold">Cancelled</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $stats['total_orders'] > 0 ? round(($stats['cancelled_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .table th {
            font-weight: 600;
            color: #374151;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
    </style>
@endsection
