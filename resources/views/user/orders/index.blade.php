@extends('frontend.layouts.app')

@section('title', 'My Orders - MyShop')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1 fw-bold text-dark">
                        <i class="fas fa-box-open me-2 text-primary"></i>
                        My Orders
                    </h1>
                    <p class="text-muted mb-0">View and track all your orders</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Order History
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="shipped">Shipped</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Order #</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Items</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="order-row" data-status="{{ $order->status }}">
                                <td class="align-middle">
                                    <span class="fw-semibold">#{{ $order->id }}</span>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <div class="fw-semibold">{{ $order->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @if($order->lines->count() > 0)
                                            <div class="flex-shrink-0 me-2">
                                                @if($order->lines->first()->product && $order->lines->first()->product->image)
                                                    <img src="{{ asset('storage/' . $order->lines->first()->product->image) }}" 
                                                         alt="{{ $order->lines->first()->product->name }}" 
                                                         class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $order->lines->count() }} item(s)</div>
                                            @if($order->lines->count() > 0)
                                                <small class="text-muted">{{ $order->lines->first()->product->name ?? 'Product' }}</small>
                                                @if($order->lines->count() > 1)
                                                    <small class="text-muted"> +{{ $order->lines->count() - 1 }} more</small>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="fw-semibold">${{ number_format($order->total, 2) }}</div>
                                    @if($order->discount_amount > 0)
                                        <small class="text-success">-${{ number_format($order->discount_amount, 2) }} discount</small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-outline-primary" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.print-invoice', $order) }}" class="btn btn-outline-secondary" 
                                           target="_blank" title="Print Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if($order->tracking)
                                            <a href="#" class="btn btn-outline-info" title="Track Order" 
                                               data-bs-toggle="modal" data-bs-target="#trackingModal{{ $order->id }}">
                                                <i class="fas fa-truck"></i>
                                            </a>
                                        @endif
                                        @if(in_array($order->payment_status, ['pending', 'failed']))
                                            <a href="{{ route('checkout.repay', $order) }}" class="btn btn-outline-warning" title="Repay">
                                                <i class="fas fa-credit-card"></i> Repay
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                            </div>
                            <div>
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open text-muted fs-1 mb-3"></i>
                    <h5 class="text-muted">No orders found</h5>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Tracking Modals -->
@foreach($orders as $order)
    @if($order->tracking)
    <div class="modal fade" id="trackingModal{{ $order->id }}" tabindex="-1">
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
@endforeach

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

.order-row {
    transition: all 0.3s ease;
}

.order-row:hover {
    background-color: rgba(99, 102, 241, 0.05);
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    border: none;
    color: var(--primary-color);
    padding: 0.5rem 0.75rem;
}

.page-link:hover {
    background-color: var(--primary-color);
    color: white;
}

.page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const orderRows = document.querySelectorAll('.order-row');
    
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        orderRows.forEach(row => {
            if (selectedStatus === '' || row.dataset.status === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection 