@props(['product'])
<div class="product-card-premium h-100 d-flex flex-column position-relative">

    <!-- Product Image -->
    <div class="premium-image-wrapper d-flex align-items-center justify-content-center bg-white rounded-top-4" style="width:100%;height:220px;overflow:hidden;">
       <a href="{{ route('model.show', $product) }}">
           <img src="{{ asset('storage/' . $product->thumbnail) }}" class="premium-product-image" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:contain;display:block;">

       </a>
    </div>
    <!-- Card Body -->
    <div class="premium-card-body flex-grow-1 d-flex flex-column justify-content-between p-4 bg-white rounded-bottom-4">
        <div>
            <!-- Brand and Category -->
            <div class="d-flex align-items-center mb-2" style="gap: 0.75rem;">
                @if($product->qualiification)
                    <span class="text-muted small d-flex align-items-center" style="font-family: 'Playfair Display', serif; letter-spacing: 0.02em;">
                        <i class="bi bi-bookmark-star me-1" style="color: var(--primary-color);"></i>
                        {{ $product->qualiification->title }}
                    </span>
                @endif
                @if($product->examboard)
                    <span class="text-muted small d-flex align-items-center" style="font-family: 'Playfair Display', serif; letter-spacing: 0.02em;">
                        <i class="bi bi-journal-bookmark me-1" style="color: var(--secondary-color);"></i>
                        {{ $product->examboard->title }}
                    </span>
                @endif
            </div>
            <h5 class="premium-title mb-2">
                <a class="text-decoration-none" style="color: var(--primary-color);"
                    href="{{ route('model.show', $product) }}">{{ $product->name }}
                </a>
            </h5>
            <p class="premium-desc text-muted mb-3">{{ Str::limit(strip_tags($product->description), 80) }}</p>
        </div>
        <div class="mt-auto">
            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                @if(method_exists($product, 'hasVariants') && $product->hasVariants())
                    @php
                        $minPrice = $product->getMinPrice();
                        $maxPrice = $product->getMaxPrice();
                    @endphp
                    <span class="premium-price">
                        @if($minPrice == $maxPrice)
                            ${{ number_format($minPrice, 2) }}
                        @else
                            ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}
                        @endif
                    </span>
                    @if($product->original_price && $product->original_price > $minPrice)
                        <span class="premium-original-price ms-2">${{ number_format($product->original_price, 2) }}</span>
                    @endif
                @else
                    <span class="premium-price">${{ number_format($product->price, 2) }}</span>
                    @if($product->original_price && $product->original_price > $product->price)
                        <span class="premium-original-price ms-2">${{ number_format($product->original_price, 2) }}</span>
                    @endif
                @endif
            </div> --}}
            {{-- @if($product->getStock() == 0 && !$product->is_digital)
                <button class="btn btn-premium btn-add-to-cart w-100 py-2" disabled>
                    <i class="bi bi-cart-x me-2"></i> Out of Stock
                </button>
            @elseif(method_exists($product, 'hasVariants') && $product->hasVariants()) --}}
                {{-- <a href="{{ route('products.show', $product) }}" class="btn btn-premium btn-add-to-cart w-100 py-2">
                    <i class="bi bi-sliders me-2"></i> Select Options
                </a> --}}
            {{-- @else
                <button class="btn btn-premium btn-add-to-cart w-100 py-2" onclick="addToCart({{ $product->id }})">
                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                </button>
            @endif --}}
        </div>
    </div>
</div> 