@extends('frontend.layouts.app')

@section('title', 'Download - MyShop')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-dark">
                            <i class="fas fa-box-open me-2 text-primary"></i>
                            My Products
                        </h1>
                        <p class="text-muted mb-0">View and track all your orders</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        <div class="filter_new my-4">

            <form id="filterForm" method="GET" action="{{ route('user.orders.download') }}">
    <div class="row g-3">
        <!-- Search -->
        <div class="col-lg-9 col-md-7 col-sm-12">
            <label for="search" class="form-label">Search Product</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Search by name, description, or SKU...">
            </div>
        </div>
   

    
       
        <!-- Filter Actions -->
        <div class="col-lg-3 col-md-5 col-sm-12 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Apply Filters
                </button>
                <a href="{{ route('user.orders.download') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear
                </a>
            </div>
        </div>
    </div>
</form>
        </div>

        <!-- Orders List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Products History
                    </h5>
                    {{-- <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="shipped">Shipped</option>
                        </select>
                    </div> --}}
                </div>
            </div>
            <div class="card-body p-0">
                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    
                                    <th class="border-0">Name</th>
                                    <th class="border-0">Qualification</th>
                                    <th class="border-0">Resource</th>
                                    <th class="border-0">Subject</th>
                                    <th class="border-0">Exam Board</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="order-row" data-status="{{ $product->name }}">
                                        <td class="align-middle">
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                        </td>
                                     
                                        <td class="align-middle">
                                            <span class="fw-semibold">{{ $product->qualiification->title }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold">{{ $product->resource->title }}</span>
                                        </td>
                                        <td class="align-middle">
                                           <span class="fw-semibold">{{ $product->subject->title }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold">{{ $product->examboard->title }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{route('user.products.download', $product->id)}}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Download</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="card-footer bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                                    orders
                                </div>
                                <div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open text-muted fs-1 mb-3"></i>
                        <h5 class="text-muted">No products found</h5>
                        <p class="text-muted">You haven't placed any products yet.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            const statusFilter = document.getElementById('statusFilter');
            const orderRows = document.querySelectorAll('.order-row');

            statusFilter.addEventListener('change', function () {
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