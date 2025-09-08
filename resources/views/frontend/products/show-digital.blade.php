@extends('frontend.layouts.app')

@section('title', $product->name . ' - MyShop')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name ?? 'Category' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <img id="mainImage" src="{{ $product->image_url ?? 'https://via.placeholder.com/600x400?text=No+Image' }}" 
                     class="card-img-top" alt="{{ $product->name }}" style="height: 600px; object-fit: contain; background: #f8f9fa;">
                @if($product->gallery_urls && count($product->gallery_urls) > 0)
                    <div class="thumbnail-gallery mt-3 d-flex gap-2 px-3 pb-2">
                        <div class="thumbnail-item" style="width: 100px; height: 120px; overflow: hidden; border-radius: 6px; border: 2px solid #007bff; background: #f8f9fa; cursor:pointer;" data-img="{{ $product->image_url }}">
                            <img src="{{ $product->image_url }}" alt="Main Image" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        @foreach($product->gallery_urls as $galleryImage)
                            <div class="thumbnail-item" style="width: 100px; height: 120px; overflow: hidden; border-radius: 6px; border: 1.5px solid #e0e0e0; background: #f8f9fa; cursor:pointer;" data-img="{{ $galleryImage }}">
                                <img src="{{ $galleryImage }}" alt="Gallery Image" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-3">{{ $product->name }}</h1>
                    <div class="mb-3">
                        <span class="price fs-2">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="mb-4">
                        <h6>Description</h6>
                        <p class="text-muted">{{ $product->description ?: 'No description available.' }}</p>
                    </div>
                    <!-- Add to Cart Form (no quantity selector) -->
                    <form id="add-to-cart-form-digital" class="mb-4">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
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
                        <i class="bi bi-grid"></i> Related Products
                    </h3>
                </div>
            </div>
            <div class="col-12">
                <div class="row" id="relatedProductsContainer">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-6 col-lg-3 mb-4 related-product-item">
                            <x-product-card :product="$relatedProduct" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Add to cart for digital product (always quantity 1)
    $(document).on('submit', '#add-to-cart-form-digital', function(e) {
        e.preventDefault();
        const button = $(this).find('button[type="submit"]');
        const originalText = button.html();
        button.html('Adding...');
        button.prop('disabled', true);
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: {{ $product->id }},
                quantity: 1
            },
            success: function(response) {
                if (response.success) {
                    showToast('Product added to cart successfully!', 'success');
                    updateCartCount(response.cart_count);
                } else {
                    showToast(response.message, 'danger');
                }
                button.html(originalText);
                button.prop('disabled', false);
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'Failed to add product to cart', 'danger');
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    });

    // Gallery thumbnail click to change main image
    $(document).on('click', '.thumbnail-item', function() {
        var imgUrl = $(this).data('img');
        $('#mainImage').attr('src', imgUrl);
        $('.thumbnail-item').css('border', '1.5px solid #e0e0e0');
        $(this).css('border', '2px solid #007bff');
    });
});
</script>
@endpush 