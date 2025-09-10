<form id="filterForm" method="GET" action="{{ route('model.index') }}">
    <div class="row g-3">
        <!-- Search -->
        <div class="col-12">
            <label for="search" class="form-label">Search Model Essays</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Search by name, description, or SKU...">
            </div>
        </div>
        {{-- <!-- Category Filter -->
        <div class="col-12">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->slug }}" {{ request('category')==$category->slug ? 'selected' : '' }}>
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
                <option value="{{ $brand->id }}" {{ request('brand')==$brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>
        </div> --}}

        <!-- Resource Filter -->
        <div class="col-12">
            <label for="resource" class="form-label">Resource</label>
            <select class="form-select" id="resource" name="resource">
                <option value="">All Resources</option>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" {{ request('resource') == $resource->id ? 'selected' : '' }}>
                        {{ $resource->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Qualiification Filter -->
        <div class="col-12">
            <label for="qualiification" class="form-label">Qualiification</label>
            <select class="form-select" id="qualiification" name="qualiification">
                <option value="">All Qualiification</option>
                @foreach($qualiifications as $qualiification)
                    <option value="{{ $qualiification->id }}" {{ request('qualiification') == $qualiification->id ? 'selected' : '' }}>
                        {{ $qualiification->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Subject Filter -->
        <div class="col-12">
            <label for="subject" class="form-label">Subject</label>
            <select class="form-select" id="subject" name="subject">
                <option value="">All Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Examboard Filter -->
        <div class="col-12">
            <label for="examboard" class="form-label">Examboard</label>
            <select class="form-select" id="examboard" name="examboard">
                <option value="">All Examboard</option>
                @foreach($examboards as $examboard)
                    <option value="{{ $examboard->id }}" {{ request('examboard') == $examboard->id ? 'selected' : '' }}>
                        {{ $examboard->title }}
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
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
        </div>
        <!-- Filter Actions -->
        <div class="col-12 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Apply Filters
                </button>
                <a href="{{ route('model.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear
                </a>
            </div>
        </div>
    </div>
</form>