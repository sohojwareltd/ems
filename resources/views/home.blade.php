@extends('frontend.layouts.app')

@section('title', 'Eterna Reads - Your Literary Haven')

@section('content')
<!-- Hero Section with Carousel -->
@php
    $sliders = \App\Models\Slider::active()->ordered()->get();
@endphp

@if($sliders->count() > 0)
<section class="hero-section">
    <div class="container">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($sliders as $index => $slider)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="hero-content">
                                    <h1 class="display-4 fw-bold mb-4">
                                        {{ $slider->title }}
                                    </h1>
                                    <p class="lead mb-4">
                                        {{ $slider->description }}
                                    </p>
                                    <div class="hero-buttons">
                                        @if($slider->button_text && $slider->button_url)
                                            <a href="{{ $slider->button_url }}" class="btn btn-primary btn-lg me-3 mb-2" style="background-color: {{ $slider->button_color }}; border-color: {{ $slider->button_color }};">
                                                {{ $slider->button_text }}
                                            </a>
                                        @endif
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg mb-2">
                                            <i class="bi bi-book me-2"></i>Browse All
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image text-center">
                                    <img src="{{ $slider->image_url }}" 
                                         alt="{{ $slider->title }}" 
                                         class="img-fluid rounded-3 shadow-lg" 
                                         style="max-height: 400px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>
@else
<!-- Fallback Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Discover Your Next Great Read
                    </h1>
                    <p class="lead mb-4">
                        Welcome to Eterna Reads, your literary haven for physical books, audiobooks, and curated gift boxes. 
                        Immerse yourself in stories that inspire, educate, and entertain.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-3 mb-2">
                            <i class="bi bi-book me-2"></i>Browse Books
                        </a>
                        <a href="{{ route('products.index', ['category' => 'gift-boxes']) }}" class="btn btn-outline-primary btn-lg mb-2">
                            <i class="bi bi-gift me-2"></i>Gift Boxes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt="Eterna Reads - Literary Haven" 
                         class="img-fluid rounded-3 shadow-lg" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Quick Navigation Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">Explore Our Collection</h2>
            <p class="section-subtitle">Find exactly what you're looking for</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="quick-link-card text-center p-4 h-100">
                    <div class="quick-link-icon mb-3">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                    <h4 class="h5 mb-3">Shop Books</h4>
                    <p class="text-muted mb-3">Explore our curated collection of physical books across all genres.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Browse Books</a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quick-link-card text-center p-4 h-100">
                    <div class="quick-link-icon mb-3">
                        <i class="bi bi-gift fs-1"></i>
                    </div>
                    <h4 class="h5 mb-3">Gift Boxes</h4>
                    <p class="text-muted mb-3">Curated collections perfect for any occasion and book lover.</p>
                    <a href="{{ route('products.index', ['category' => 'gift-boxes']) }}" class="btn btn-outline-primary">View Boxes</a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quick-link-card text-center p-4 h-100">
                    <div class="quick-link-icon mb-3">
                        <i class="bi bi-headphones fs-1"></i>
                    </div>
                    <h4 class="h5 mb-3">Audiobooks</h4>
                    <p class="text-muted mb-3">Listen to your favorite stories anywhere, anytime.</p>
                    <a href="{{ route('products.index', ['category' => 'audiobooks']) }}" class="btn btn-outline-primary">Listen Now</a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quick-link-card text-center p-4 h-100">
                    <div class="quick-link-icon mb-3">
                        <i class="bi bi-envelope fs-1"></i>
                    </div>
                    <h4 class="h5 mb-3">Contact Us</h4>
                    <p class="text-muted mb-3">Have questions? We'd love to hear from you.</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Get in Touch</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Eterna Reads Section -->
<section class="py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3">
                <div class="about-content">
                    <h2 class="section-title mb-4">About Eterna Reads</h2>
                    <p class="lead mb-4">
                        Founded with a passion for literature and storytelling, Eterna Reads is more than just a bookstore. 
                        We're a community of book lovers dedicated to bringing you the finest selection of books, audiobooks, 
                        and thoughtfully curated gift boxes.
                    </p>
                    <p class="mb-4">
                        Our mission is to inspire a love for reading in everyone, from avid bookworms to those just beginning 
                        their literary journey. We believe that every book has the power to transform, educate, and entertain.
                    </p>
                    <div class="about-stats row text-center">
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold" style="color: var(--primary-color);">1000+</h3>
                                <p class="text-muted">Books Available</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold" style="color: var(--primary-color);">50+</h3>
                                <p class="text-muted">Gift Boxes</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold" style="color: var(--primary-color);">500+</h3>
                                <p class="text-muted">Happy Readers</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="btn btn-primary btn-lg mt-4">
                        Learn More About Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image text-center">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt="About Eterna Reads" 
                         class="img-fluid rounded-3 shadow-lg" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">Featured Products</h2>
            <p class="section-subtitle">Discover our handpicked selection of must-read books and exclusive gift boxes</p>
        </div>

        @php
            $featuredProducts = \App\Models\Product::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        @endphp

        @if($featuredProducts->count() > 0)
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <x-product-card :product="$product" />
                </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-arrow-right me-2"></i>View All Products
                </a>
            </div>
        @else
            <div class="text-center">
                <div class="py-5">
                    <i class="bi bi-book fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No products available at the moment</h4>
                    <p class="text-muted">Check back soon for our latest collection!</p>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Testimonials Section -->
@php
    $featuredReviews = \App\Models\Review::featured()->active()->ordered()->limit(3)->get();
@endphp

@if($featuredReviews->count() > 0)
<section class="py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">What Our Readers Say</h2>
            <p class="section-subtitle">Join our community of satisfied book lovers</p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredReviews as $review)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        @if($review->avatar)
                        <div class="mb-3">
                            <img src="{{ $review->avatar_url }}" 
                                 alt="{{ $review->name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                        @endif
                        <div class="mb-3">
                            {!! $review->stars_html !!}
                        </div>
                        <p class="card-text mb-3">
                            "{{ $review->content }}"
                        </p>
                        <h6 class="card-title mb-1">{{ $review->name }}</h6>
                        @if($review->title)
                        <small class="text-muted">{{ $review->title }}</small>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="py-5" style="background: var(--primary-color); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="mb-3">Stay Updated</h3>
                <p class="mb-0">Subscribe to our newsletter for book recommendations, exclusive offers, and literary news.</p>
            </div>
            <div class="col-lg-6">
                <div class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Enter your email address">
                    <button class="btn btn-light">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
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
            updateCartCount();
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
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
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
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
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
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
</script>
@endsection
