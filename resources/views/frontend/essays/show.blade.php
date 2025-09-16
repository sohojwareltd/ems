@extends('frontend.layouts.app')

@section('title', $product->name . ' - MyShop')

@section('content')
<style>
    .variant-option {
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.3s, background 0.3s;
        background: #fff;
        cursor: pointer;
        position: relative;
    }

    .variant-option:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .variant-checkmark {
        display: none;
        font-size: 1.3rem;
        color: #28a745;
        margin-left: 16px;
        margin-right: 0;
    }

    .variant-option .form-check-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .variant-option .form-check-label {
        flex: 1;
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }

    .variant-option .variant-price {
        font-weight: 600;
        color: #28a745;
        font-size: 1.1rem;
        margin-left: 16px;
    }

    .variant-option input[type="radio"]:checked + .form-check-label {
        color: #222;
        font-weight: 700;
    }

    .variant-option input[type="radio"]:checked ~ .variant-price {
        font-weight: 700;
        color: #218838;
    }

    .variant-option input[type="radio"]:checked ~ .variant-checkmark {
        display: inline-block;
    }

    .variant-option input[type="radio"]:checked ~ .form-check-label {
        color: #222;
        font-weight: 700;
    }

    .variant-option input[type="radio"]:checked ~ .variant-option {
        border-color: #b2dfdb;
        background: #e0f7fa;
    }

    .variant-option input[type="radio"]:checked {
        border-color: #b2dfdb;
        background-color: #e0f7fa;
    }

    .variant-option input[type="radio"]:focus {
        box-shadow: none;
    }

    .variant-option .form-check-input[type="radio"]:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .variant-option .form-check-input:checked ~ .variant-option {
        border-color: #007bff;
        background-color: #e3f2fd;
    }

    .variant-price {
        font-weight: 600;
        color: #28a745;
    }

    .variant-label {
        font-weight: 500;
    }

    .variants-container {
        max-height: 300px;
        overflow-y: auto;
    }

    /* Image Gallery Styles */
    .product-gallery {
        position: relative;
    }

    .main-image-container {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
        cursor: pointer;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image {
        width: 100%;
        height: 600px;
        object-fit: contain;
        transition: transform 0.3s ease;
        background: #f8f9fa;
    }

    .main-image:hover {
        transform: scale(1.02);
    }

    .thumbnail-gallery {
        margin-top: 15px;
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .thumbnail-item {
        flex: 0 0 100px;
        height: 120px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .thumbnail-item:hover {
        border-color: #007bff;
        transform: translateY(-2px);
    }

    .thumbnail-item.active {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
    }

    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #f8f9fa;
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .main-image-container:hover .gallery-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .gallery-overlay i {
        color: white;
        font-size: 2rem;
    }

    /* Lightbox Styles */
    .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: none;
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: none;
        border: none;
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .lightbox-nav:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
    }

    /* List View Styles */
    .list-view .related-product-item {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }

    .list-view .product-card {
        flex-direction: row !important;
        height: auto !important;
    }

    .list-view .product-card .position-relative {
        width: 200px;
        flex-shrink: 0;
    }

    .list-view .product-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .list-view .product-card .card-img-top {
        height: 150px !important;
        object-fit: cover;
    }

    .list-view .product-card .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .list-view .product-card .card-text {
        margin-bottom: 1rem;
    }

    .list-view .product-card .mt-auto {
        margin-top: 0 !important;
    }

    .list-view .product-card .d-flex.gap-2 {
        justify-content: flex-start;
    }

    .list-view .product-card .btn {
        min-width: 100px;
    }
</style>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('model.index') }}">Model Essays</a></li>
            {{-- <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name ?? 'Category' }}</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-container" id="mainImageContainer">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         class="main-image" id="mainImage" alt="{{ $product->name }}">

                    <div class="gallery-overlay">
                        <i class="bi bi-zoom-in"></i>
                    </div>

                    @if($product->discount_price)
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger fs-6">
                                {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                            </span>
                        </div>
                    @endif

                    @if($product->is_featured)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-star"></i> Featured
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                <div class="thumbnail-gallery" id="thumbnailGallery">
                    <!-- Thumbnails will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-3">{{ $product->name }}</h1>

                    
                    <!-- Product Meta -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-eye"></i> {{ $product->views ?? 0 }} views
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6>Description</h6>
                        <p class="text-muted">{!! $product->description ?: 'No description available.' !!}</p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form id="add-to-cart-form" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <a href="{{route('subscriptions.index')}}" class="btn custom-btn w-100 btn-lg">
                                     Subscription
                                </a>
                                {{-- @endif --}}
                                
                            </div>
                            
                        </div>
                    </form>

                    <!-- Product Features -->
                    @if($product->features)
                        <div class="mb-4">
                            <h6>Features</h6>
                            <ul class="list-unstyled">
                                @foreach(json_decode($product->features, true) ?? [] as $feature)
                                    <li><i class="bi bi-check text-success"></i> {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Share -->
                    <div class="border-top pt-3">
                        <div class="d-flex gap-2">
                            <a href="{{route('model.index', ['qualiification'=>$product->qualiification->id])}}" class="btn btn-outline-primary btn-sm">
                                {{$product->qualiification->title ?? ''}}
                            </a>
                            <a href="{{route('model.index', ['examboard'=>$product->examboard->id])}}" class="btn btn-outline-info btn-sm">
                                 {{$product->examboard->title ?? ''}}
                            </a>
                            <a href="{{route('model.index', ['resource'=>$product->resource->id])}}" class="btn btn-outline-success btn-sm">
                                 {{$product->resource->title ?? ''}}
                            </a>
                            <a href="{{route('model.index', ['subject'=>$product->subject->id])}}" class="btn btn-outline-success btn-sm">
                                 {{$product->subject->title ?? ''}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="bi bi-grid"></i> Related Model Essays
                    </h3>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted">View:</span>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn custom-btn-outline btn-sm active" id="gridView">
                                <i class="bi bi-grid"></i>
                            </button>
                            <button type="button" class="btn custom-btn-outline btn-sm" id="listView">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row" id="relatedProductsContainer">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-6 col-lg-3 mb-4 related-product-item">
                            <x-essay-card :product="$relatedProduct" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">
            <i class="bi bi-x"></i>
        </button>
        <img src="" alt="" class="lightbox-image" id="lightboxImage">
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Product gallery data
    const productGallery = {
        images: [],
        currentIndex: 0,
        currentVariantIndex: -1
    };

    // Initialize gallery on page load
    $(function() {
        initializeGallery();
        initializeVariantSelection();
        initializeLightbox();

        // Variant selection handling
        $(document).on('change', '.variant-radio', function() {
            updateVariantDisplay();
        });

        function initializeGallery() {
    productGallery.images = [];

    // Add main thumbnail (always first image)
    const thumbnailUrl = '{{ $product->thumbnail ? asset("storage/" . $product->thumbnail) : "https://via.placeholder.com/600x400?text=No+Image" }}';
    productGallery.images.push(thumbnailUrl);

    // Add gallery images (if exists)
    @if($product->gallery && count($product->gallery) > 0)
        @foreach($product->gallery as $galleryImage)
            productGallery.images.push('{{ asset("storage/" . $galleryImage) }}');
        @endforeach
    @endif

    // Remove duplicates (thumbnail + gallery)
    productGallery.images = [...new Set(productGallery.images)];

    // Build thumbnail gallery
    buildThumbnailGallery();
}


        function buildThumbnailGallery() {
            const thumbnailGallery = $('#thumbnailGallery');
            thumbnailGallery.empty();

            productGallery.images.forEach((image, index) => {
                const thumbnailItem = $(`
                    <div class="thumbnail-item ${index === 0 ? 'active' : ''}" data-index="${index}">
                        <img src="${image}" alt="Product Image ${index + 1}" class="thumbnail-image">
                    </div>
                `);

                thumbnailItem.on('click', function() {
                    setActiveImage(index);
                });

                thumbnailGallery.append(thumbnailItem);
            });
        }

        function setActiveImage(index) {
            productGallery.currentIndex = index;
            const imageUrl = productGallery.images[index];

            // Update main image
            $('#mainImage').attr('src', imageUrl);

            // Update thumbnail active state
            $('.thumbnail-item').removeClass('active');
            $(`.thumbnail-item[data-index="${index}"]`).addClass('active');
        }

        function initializeVariantSelection() {
            // Initialize variant selection on page load
            updateVariantDisplay();
        }

        function updateVariantDisplay() {
            const selectedVariant = $('.variant-radio:checked');
            const quantityInput = $('#quantity');

            if (selectedVariant.length === 0) {
                // No variant selected, show base product price and stock
                $('.price').text('${{ number_format($product->price, 2) }}');
                setActiveImage(0); // Show main product image
                $('#variant-sku').text('');
                $('#variant-stock').text('');
                quantityInput.attr('max', 1);
                quantityInput.val(1);
            } else {
                // Single variant selected
                const price = parseFloat(selectedVariant.data('price'));
                const stock = parseInt(selectedVariant.data('stock'));
                const sku = selectedVariant.data('sku');
                const isOutOfStock = {{ $product->track_quantity ? 'true' : 'false' }} && stock <= 0;

                // Update price display
                $('.price').text('$' + price.toFixed(2));

                // Update product image if variant has a specific image
                const variantData = selectedVariant.data('variant');
                if (variantData && variantData.image) {
                    // Find variant image in gallery by constructing the full path
                    const variantImagePath = "{{ asset('storage/') }}/" + variantData.image;
                    const variantImageIndex = productGallery.images.findIndex(img => img === variantImagePath);

                    if (variantImageIndex !== -1) {
                        setActiveImage(variantImageIndex);
                    }
                } else {
                    // Show main product image if variant has no specific image
                    setActiveImage(0);
                }

                // Update SKU and stock display
                $('#variant-sku').text(sku);
                $('#variant-stock').text(stock);

                // Update quantity max value and reset if needed
                quantityInput.attr('max', stock);
                if (parseInt(quantityInput.val()) > stock) {
                    quantityInput.val(stock > 0 ? stock : 1);
                }
                if (isOutOfStock) {
                    $('#add-to-cart-form button[type="submit"]').prop('disabled', true).text('Out of Stock');
                } else {
                    $('#add-to-cart-form button[type="submit"]').prop('disabled', false).html('<i class="bi bi-cart-plus"></i> Add to Cart');
                }
            }
        }

        function initializeLightbox() {
            // Open lightbox on main image click
            $('#mainImageContainer').on('click', function() {
                openLightbox(productGallery.currentIndex);
            });

            // Close lightbox
            $('#lightboxClose, #lightbox').on('click', function(e) {
                if (e.target === this) {
                    closeLightbox();
                }
            });

            // Navigation
            $('#lightboxPrev').on('click', function() {
                navigateLightbox(-1);
            });

            $('#lightboxNext').on('click', function() {
                navigateLightbox(1);
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($('#lightbox').hasClass('active')) {
                    switch(e.key) {
                        case 'Escape':
                            closeLightbox();
                            break;
                        case 'ArrowLeft':
                            navigateLightbox(-1);
                            break;
                        case 'ArrowRight':
                            navigateLightbox(1);
                            break;
                    }
                }
            });
        }

        function openLightbox(index) {
            productGallery.currentIndex = index;
            const imageUrl = productGallery.images[index];

            $('#lightboxImage').attr('src', imageUrl);
            $('#lightbox').addClass('active');
            $('body').css('overflow', 'hidden');
        }

        function closeLightbox() {
            $('#lightbox').removeClass('active');
            $('body').css('overflow', '');
        }

        function navigateLightbox(direction) {
            let newIndex = productGallery.currentIndex + direction;

            if (newIndex < 0) {
                newIndex = productGallery.images.length - 1;
            } else if (newIndex >= productGallery.images.length) {
                newIndex = 0;
            }

            openLightbox(newIndex);
        }

        // Add to cart form submission
        $(document).on('submit', '#add-to-cart-form', function(e) {
            e.preventDefault();

            const button = $(this).find('button[type="submit"]');
            const originalText = button.html();

            // Show loading state
            button.html('Adding...');
            button.prop('disabled', true);

            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: {
                    product_id: {{ $product->id }},
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Product added to cart successfully!', 'success');
                        updateCartCount(response.cart_count);
                    } else {
                        showToast(response.message, 'danger');
                    }
                    // Restore button state
                    button.html(originalText);
                    button.prop('disabled', false);
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast(response?.message || 'Failed to add product to cart', 'danger');
                    // Restore button state on error
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            });
        });

        // Add to cart function for related products
        window.addToCart = function(productId) {
            const button = event.target;
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML = 'Adding...';
            button.disabled = true;

            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        updateCartCount(response.cart_count);
                    } else {
                        showToast(response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast(response?.message || 'Failed to add product to cart', 'danger');
                },
                complete: function() {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            });
        };

        // Quantity validation
        $(document).on('input', '#quantity', function() {
            const value = parseInt($(this).val());
            const max = parseInt($(this).attr('max'));

            if (value > max) {
                $(this).val(max);
            } else if (value < 1) {
                $(this).val(1);
            }
        });

        // Grid/List View Toggle
        $(document).on('click', '#gridView, #listView', function() {
            const isGrid = $(this).attr('id') === 'gridView';
            const relatedProductsContainer = $('#relatedProductsContainer');

            if (isGrid) {
                relatedProductsContainer.removeClass('list-view');
                $('#gridView').addClass('active');
                $('#listView').removeClass('active');
            } else {
                relatedProductsContainer.addClass('list-view');
                $('#listView').addClass('active');
                $('#gridView').removeClass('active');
            }
        });
    });
</script>
@endpush
