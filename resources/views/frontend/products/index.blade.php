@extends('frontend.layouts.app')

@section('title', 'Shop - Eterna Reads')
@section('meta_description', 'Explore our collection of books, audiobooks, and gift boxes. Find your next great read or
    the perfect gift for a book lover.')
@section('meta_keywords', 'books, audiobooks, gift boxes, bookshop, online bookstore, reading, literature, book gifts')

@section('content')
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="section-header text-center mb-5">
            <h1 class="section-title display-4 fw-bold">Discover Our Collection</h1>
            <p class="section-subtitle lead">Find the perfect books, audiobooks, and gift boxes for your reading journey</p>
        </div>

        <div class="row">
            <!-- Sidebar Filters (Desktop) & Offcanvas (Mobile) -->
            <div class="col-md-4 mb-4 d-none d-md-block">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-funnel me-2"></i>Filters & Search
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('frontend.products._filters', [
                                // 'categories' => $categories,
                                // 'brands' => $brands,
                                'resources' => $resources,
                                'qualiifications' => $qualiifications,
                                'subjects' => $subjects,
                                'examboards' => $examboards,
                            ])
                        </div>
                    </div>
                </div>
            </div>
            <!-- Offcanvas Trigger (Mobile) -->
            <div class="col-12 d-md-none mb-3">
                <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#filtersOffcanvas" aria-controls="filtersOffcanvas">
                    <i class="bi bi-funnel me-2"></i>Filters & Search
                </button>
            </div>
            <!-- Offcanvas Filters (Mobile) -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="filtersOffcanvas"
                aria-labelledby="filtersOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="filtersOffcanvasLabel"><i class="bi bi-funnel me-2"></i>Filters & Search
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    @include('frontend.products._filters', [
                        // 'categories' => $categories,
                        // 'brands' => $brands,
                        'resources' => $resources,
                        'qualiifications' => $qualiifications,
                        'subjects' => $subjects,
                        'examboards' => $examboards,
                    ])
                </div>
            </div>
            <!-- Main Content: Products -->
            <div class="col-md-8">
                <!-- Active Filters Display -->
                @if (request('search') ||
                        request('resource') ||
                        request('qualiification') ||
                        request('subject') ||
                        request('examboard') ||
                        request('min_price') ||
                        request('max_price') ||
                        request('sort'))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="text-muted">Active Filters:</span>
                                @if (request('search'))
                                    <span class="badge bg-primary">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ route('products.index', request()->except('search')) }}"
                                            class="text-white text-decoration-none ms-1">×</a>
                                    </span>
                                @endif
                                @if (request('qualiification'))
                                    @php $qualiification = $qualiifications->firstWhere('id', request('qualiification')) @endphp
                                    @if ($qualiification)
                                        <span class="badge bg-primary">
                                            Qualiification: {{ $qualiification->title }}
                                            <a href="{{ route('products.index', request()->except('qualiification')) }}"
                                                class="text-white text-decoration-none ms-1">×</a>
                                        </span>
                                    @endif
                                @endif
                                @if (request('examboard'))
                                    @php $examboard = $examboards->firstWhere('id', request('examboard')) @endphp
                                    @if ($examboard)
                                        <span class="badge bg-primary">
                                            Exam Board: {{ $examboard->title }}
                                            <a href="{{ route('products.index', request()->except('examboard')) }}"
                                                class="text-white text-decoration-none ms-1">×</a>
                                        </span>
                                    @endif
                                @endif
                                @if (request('resource'))
                                    @php $resource = $resources->firstWhere('id', request('resource')) @endphp
                                    @if ($resource)
                                        <span class="badge bg-primary">
                                            Resource: {{ $resource->title }}
                                            <a href="{{ route('products.index', request()->except('resource')) }}"
                                                class="text-white text-decoration-none ms-1">×</a>
                                        </span>
                                    @endif
                                @endif
                                @if (request('subject'))
                                    @php $subject = $subjects->firstWhere('id', request('subject')) @endphp
                                    @if ($subject)
                                        <span class="badge bg-primary">
                                            Subject: {{ $subject->title }}
                                            <a href="{{ route('products.index', request()->except('subject')) }}"
                                                class="text-white text-decoration-none ms-1">×</a>
                                        </span>
                                    @endif
                                @endif
                                @if (request('min_price') || request('max_price'))
                                    <span class="badge bg-primary">
                                        Price: ${{ request('min_price', '0') }} - ${{ request('max_price', '∞') }}
                                        <a href="{{ route('products.index', request()->except(['min_price', 'max_price'])) }}"
                                            class="text-white text-decoration-none ms-1">×</a>
                                    </span>
                                @endif
                                @if (request('sort'))
                                    @php
                                        $sortLabels = [
                                            'name' => 'Name A-Z',
                                            'name_desc' => 'Name Z-A',
                                            'price' => 'Price Low-High',
                                            'price_desc' => 'Price High-Low',
                                            'newest' => 'Newest First',
                                            'popular' => 'Most Popular',
                                        ];
                                    @endphp
                                    <span class="badge bg-primary">
                                        Sort: {{ $sortLabels[request('sort')] ?? request('sort') }}
                                        <a href="{{ route('products.index', request()->except('sort')) }}"
                                            class="text-white text-decoration-none ms-1">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Results Summary -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">
                                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of
                                {{ $products->total() }} products
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if ($products->count() > 0)
                    <div class="row g-4" id="productsGrid">
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-6 ">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Products pagination">
                                {{ $products->appends(request()->query())->links() }}
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-search fs-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No products found</h4>
                        <p class="text-muted mb-4">Try adjusting your search criteria or browse our full collection.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters Partial -->
    @push('partials')
        @if (!View::exists('frontend.products._filters'))
            @php
                // Inline the filter form as a partial for DRYness
            @endphp
            @once
                @push('partials')
                    <div id="_filters-partial" style="display:none">
                        <form id="filterForm" method="GET" action="{{ route('products.index') }}">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-12">
                                    <label for="search" class="form-label">Search Products</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Search by name, description, or SKU...">
                                    </div>
                                </div>
                                <!-- Category Filter -->
                                <div class="col-12">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->slug }}"
                                                {{ trim((string) request('category')) === trim((string) $category->slug) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Brand Filter -->
                                <div class="col-12">
                                    <label for="brand" class="form-label">Brand</label>
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">All Brands</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Sort -->
                                <div class="col-12">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First
                                        </option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A
                                        </option>
                                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low-High
                                        </option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price
                                            High-Low</option>
                                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular
                                        </option>
                                    </select>
                                </div>
                                <!-- Price Range -->
                                <div class="col-12">
                                    <label class="form-label">Price Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control" id="min_price" name="min_price"
                                                value="{{ request('min_price') }}" placeholder="Min Price" min="0">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control" id="max_price" name="max_price"
                                                value="{{ request('max_price') }}" placeholder="Max Price" min="0">
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter Actions -->
                                <div class="col-12 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="bi bi-search me-2"></i>Apply Filters
                                        </button>
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endpush
            @endonce
        @endif
    @endpush

    <script>
        function addToCart(productId) {
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        if (typeof data.cart_count !== 'undefined') {
                            document.getElementById('cart-count').textContent = data.cart_count;
                        }
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className =
                            'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                        alert.style.zIndex = '9999';
                        alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                Product added to cart successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                        document.body.appendChild(alert);

                        // Auto-remove after 3 seconds
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 3000);
                    } else {
                        // Show error message
                        const alert = document.createElement('div');
                        alert.className =
                            'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                        alert.style.zIndex = '9999';
                        alert.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${data.message || 'Error adding product to cart'}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                        document.body.appendChild(alert);

                        // Auto-remove after 3 seconds
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                    const alert = document.createElement('div');
                    alert.className =
                        'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>
            Error adding product to cart
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
                    document.body.appendChild(alert);

                    // Auto-remove after 3 seconds
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 3000);
                });
        }

        // View toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const productsGrid = document.getElementById('productsGrid');

            if (gridView && listView && productsGrid) {
                gridView.addEventListener('click', function() {
                    gridView.classList.add('active');
                    listView.classList.remove('active');
                    productsGrid.className = 'row g-4';
                });

                listView.addEventListener('click', function() {
                    listView.classList.add('active');
                    gridView.classList.remove('active');
                    productsGrid.className = 'row g-3';
                    productsGrid.querySelectorAll('.col-md-6, .col-lg-4, .col-xl-3').forEach(col => {
                        col.className = 'col-12';
                    });
                });
            }
        });
    </script>
@endsection
