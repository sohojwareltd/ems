<form id="filterForm" method="GET" action="{{ route('products.index') }}">
    <div class="row g-3">
        <!-- Search -->
        <div class="col-12">
            <label for="search" class="form-label">Search Products</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by name, description, or SKU...">
            </div>
        </div>
        <!-- Category Filter -->
        <div class="col-12">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
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
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Sort -->
        <div class="col-12">
            <label for="sort" class="form-label">Sort By</label>
            <select class="form-select" id="sort" name="sort">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low-High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
        </div>
        <!-- Price Range -->
        <div class="col-12">
            <label class="form-label">Price Range</label>
            <div class="row g-2">
                <div class="col-6">
                    <input type="number" 
                           class="form-control" 
                           id="min_price" 
                           name="min_price" 
                           value="{{ request('min_price') }}"
                           placeholder="Min Price"
                           min="0">
                </div>
                <div class="col-6">
                    <input type="number" 
                           class="form-control" 
                           id="max_price" 
                           name="max_price" 
                           value="{{ request('max_price') }}"
                           placeholder="Max Price"
                           min="0">
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