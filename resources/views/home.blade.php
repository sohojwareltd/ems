@extends('frontend.layouts.app')

{{-- @section('title', 'Eterna Reads - Your Literary Haven') --}}
<style>
    #courses {
        scroll-margin-top: 100px;
        /* Adjust the value as needed */
    }
</style>

@section('content')
    <!-- Hero Section with Carousel -->
    @php
        $sliders = \App\Models\Slider::active()->ordered()->get();
        $essays = \App\Models\Essay::latest()->limit(4)->get();

    @endphp

    @if ($sliders->count() > 0)
        <section class="hero-section">
            <div class="container">
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($sliders as $index => $slider)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($sliders as $index => $slider)
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
                                                @if ($slider->button_text && $slider->button_url)
                                                    <a href="{{ $slider->button_url }}"
                                                        class="btn btn-primary btn-lg me-3 mb-2 text-white"
                                                        style="background-color: {{ $slider->button_color }}; border-color: {{ $slider->button_color }};z-index:11;">
                                                        {{ $slider->button_text }}
                                                    </a>
                                                @endif
                                                {{-- <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg mb-2">
                                            <i class="bi bi-book me-2"></i>Browse All
                                        </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="hero-image text-center">
                                            <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                                                class="img-fluid rounded-3 shadow-lg"
                                                style="max-height: 400px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="next">
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
                                Welcome to EMS, your literary haven for physical books, audiobooks, and curated
                                gift boxes.
                                Immerse yourself in stories that inspire, educate, and entertain.
                            </p>
                            <div class="hero-buttons">
                                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-3 mb-2">
                                    <i class="bi bi-book me-2"></i>Browse Books
                                </a>
                                <a href="{{ route('products.index', ['category' => 'gift-boxes']) }}"
                                    class="btn btn-outline-primary btn-lg mb-2">
                                    <i class="bi bi-gift me-2"></i>Gift Boxes
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image text-center">
                            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Eterna Reads - Literary Haven" class="img-fluid rounded-3 shadow-lg"
                                style="max-height: 400px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Reviews Carousel Section -->
    @php
        $featuredReviews = \App\Models\Review::featured()->active()->approved()->ordered()->get();
    @endphp

    @if ($featuredReviews->count() > 0 && setting('home.show_reviews', true))
        <section class="reviews-carousel-section py-5" style="
            background: linear-gradient(135deg, #f8f9ff 0%, var(--light-bg) 50%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        ">
            <!-- Decorative Background Elements -->
            <div style="
                position: absolute;
                top: -100px;
                right: -100px;
                width: 300px;
                height: 300px;
                background: rgba(var(--primary-color-rgb, 59, 130, 246), 0.08);
                border-radius: 50%;
                z-index: 1;
            "></div>
            <div style="
                position: absolute;
                bottom: -80px;
                left: -80px;
                width: 250px;
                height: 250px;
                background: rgba(var(--primary-color-rgb, 59, 130, 246), 0.06);
                border-radius: 50%;
                z-index: 1;
            "></div>

            <div class="container" style="position: relative; z-index: 2;">
                <!-- Section Header -->
                <div class="text-center mb-5">
                    <div class="mb-3">
                        <span class="badge" style="
                            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                            font-size: 0.85rem;
                            padding: 0.6rem 1.2rem;
                            border-radius: 50px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        ">
                            <i class="bi bi-star-fill me-2"></i>WHAT USERS SAY
                        </span>
                    </div>
                    <h2 class="section-title mb-3" style="
                        color: var(--primary-color);
                        font-weight: 800;
                        font-size: 2.5rem;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
                    ">
                        {{ setting('home.reviews_heading', 'What Our Users Say') }}
                    </h2>
                    <p class="section-subtitle text-muted" style="font-size: 1.1rem; font-weight: 500;">
                        {{ setting('home.reviews_subtitle', 'Join thousands of satisfied learners worldwide') }}
                    </p>
                </div>

                <!-- Carousel Wrapper -->
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-pause="hover" data-bs-interval="5000">
                            <div class="carousel-inner">
                                @foreach ($featuredReviews as $index => $review)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="review-card" style="
                                            background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%);
                                            border: 2px solid var(--primary-color);
                                            border-radius: 20px;
                                            padding: 45px;
                                            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
                                            min-height: 340px;
                                            display: flex;
                                            flex-direction: column;
                                            justify-content: space-between;
                                            position: relative;
                                            transition: all 0.4s ease;
                                        ">
                                            <!-- Quote Icon Background -->
                                            <div style="
                                                position: absolute;
                                                top: -15px;
                                                right: 30px;
                                                font-size: 4rem;
                                                color: rgba(var(--primary-color-rgb, 59, 130, 246), 0.1);
                                                font-weight: bold;
                                            ">
                                                "
                                            </div>

                                            <!-- Stars with Animation -->
                                            <div style="
                                                display: flex;
                                                gap: 8px;
                                                justify-content: center;
                                                font-size: 1.8rem;
                                                margin-bottom: 25px;
                                            ">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="bi bi-star-fill review-star" style="
                                                            color: #ffc107;
                                                            text-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
                                                            animation: twinkle 0.8s ease-in-out;
                                                            animation-delay: {{ ($i - 1) * 0.1 }}s;
                                                        "></i>
                                                    @else
                                                        <i class="bi bi-star" style="color: #e8e8e8;"></i>
                                                    @endif
                                                @endfor
                                            </div>

                                            <!-- Quote -->
                                            <blockquote style="
                                                text-align: center;
                                                font-size: 1.2rem;
                                                line-height: 1.9;
                                                color: #2c3e50;
                                                margin: 0 0 35px 0;
                                                font-style: italic;
                                                font-weight: 500;
                                                letter-spacing: 0.5px;
                                            ">
                                                "{{ $review->content }}"
                                            </blockquote>

                                            <!-- Reviewer Info with Avatar -->
                                            <div style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                gap: 16px;
                                                border-top: 2px solid #f0f0f0;
                                                padding-top: 25px;
                                            ">
                                                <div style="position: relative;">
                                                    @if ($review->avatar)
                                                        <img src="{{ $review->avatar_url }}" 
                                                            alt="{{ $review->name }}"
                                                            style="
                                                                width: 70px;
                                                                height: 70px;
                                                                border-radius: 50%;
                                                                object-fit: cover;
                                                                border: 4px solid var(--primary-color);
                                                                box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                                                                transition: transform 0.3s ease;
                                                            "
                                                            class="reviewer-avatar">
                                                    @else
                                                        <div style="
                                                            width: 70px;
                                                            height: 70px;
                                                            border-radius: 50%;
                                                            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                            font-weight: bold;
                                                            font-size: 1.8rem;
                                                            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                                                            transition: transform 0.3s ease;
                                                        "
                                                        class="reviewer-avatar">
                                                            {{ substr($review->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div style="text-align: left;">
                                                    <h6 style="
                                                        margin: 0;
                                                        color: var(--primary-color);
                                                        font-weight: 700;
                                                        font-size: 1.1rem;
                                                    ">
                                                        {{ $review->name }}
                                                    </h6>
                                                    @if ($review->title)
                                                        <small style="
                                                            color: #888;
                                                            display: block;
                                                            margin: 4px 0;
                                                            font-weight: 500;
                                                        ">
                                                            {{ $review->title }}
                                                        </small>
                                                    @endif
                                                    @if ($review->country_flag_url)
                                                        <img src="{{ $review->country_flag_url }}" 
                                                            alt="Country" 
                                                            style="
                                                                width: 28px;
                                                                height: auto;
                                                                margin-top: 6px;
                                                                border-radius: 3px;
                                                                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                                                            ">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Carousel Indicators at Bottom -->
                            @if ($featuredReviews->count() > 1)
                                <div style="
                                    text-align: center;
                                    margin-top: 40px;
                                    display: flex;
                                    gap: 10px;
                                    justify-content: center;
                                    flex-wrap: wrap;
                                    padding-bottom: 10px;
                                ">
                                    @foreach ($featuredReviews as $index => $review)
                                        <button type="button" 
                                            data-bs-target="#reviewsCarousel" 
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }} review-indicator"
                                            style="
                                                width: 14px;
                                                height: 14px;
                                                border-radius: 50%;
                                                border: 2px solid {{ $index === 0 ? 'var(--primary-color)' : '#ddd' }};
                                                background-color: {{ $index === 0 ? 'var(--primary-color)' : 'transparent' }};
                                                cursor: pointer;
                                                transition: all 0.4s ease;
                                                box-shadow: {{ $index === 0 ? '0 0 10px rgba(0,0,0,0.2)' : 'none' }};
                                            "
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Share Review Button -->
                @auth
                    @if(Auth::user()->hasActiveSubscription())
                        <div class="text-center mt-5">
                            <a href="{{ route('reviews.create') }}" class="btn btn-lg review-cta-btn" style="
                                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                                border: none;
                                color: white;
                                padding: 15px 50px;
                                border-radius: 50px;
                                font-weight: 700;
                                font-size: 1.1rem;
                                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                                transition: all 0.3s ease;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            ">
                                <i class="bi bi-pencil-square me-2"></i>Share Your Experience
                            </a>
                        </div>
                    @else
                        <div class="text-center mt-5">
                            <a href="{{ route('login') }}" class="btn btn-lg review-cta-btn" style="
                                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                                border: none;
                                color: white;
                                padding: 15px 50px;
                                border-radius: 50px;
                                font-weight: 700;
                                font-size: 1.1rem;
                                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                                transition: all 0.3s ease;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            ">
                                <i class="bi bi-pencil-square me-2"></i>Login to Share Your Experience
                            </a>
                        </div>
                    @endauth
                @endauth
            </div>

            <style>
                .review-card {
                    animation: slideIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                }

                .carousel-item.active .review-card {
                    animation: slideIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                }

                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(30px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                @keyframes twinkle {
                    0% {
                        transform: scale(0.8);
                        opacity: 0.3;
                    }
                    50% {
                        transform: scale(1.2);
                    }
                    100% {
                        transform: scale(1);
                        opacity: 1;
                    }
                }

                .review-carousel-btn:hover span {
                    transform: scale(1.2);
                    box-shadow: 0 10px 30px rgba(0,0,0,0.25) !important;
                }

                .reviewer-avatar:hover {
                    transform: scale(1.12) !important;
                }

                .review-cta-btn:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 15px 40px rgba(0,0,0,0.2) !important;
                }

                .review-cta-btn:active {
                    transform: translateY(-2px);
                }

                .review-indicator {
                    transition: all 0.3s ease;
                }

                .review-indicator.active {
                    box-shadow: 0 0 15px rgba(var(--primary-color-rgb, 59, 130, 246), 0.5) !important;
                }

                .review-indicator:hover:not(.active) {
                    border-color: var(--primary-color);
                    background-color: rgba(var(--primary-color-rgb, 59, 130, 246), 0.3);
                }

                @media (max-width: 768px) {
                    .carousel-control-prev,
                    .carousel-control-next {
                        display: none;
                    }
                    
                    .review-card {
                        padding: 30px;
                        min-height: 320px;
                    }

                    .review-cta-btn {
                        padding: 12px 30px !important;
                        font-size: 0.95rem !important;
                    }

                    h2.section-title {
                        font-size: 1.8rem !important;
                    }
                    .review-indicator {
                        width: 12px !important;
                        height: 12px !important;
                    }                }
            </style>
        </section>
    @endif

    <!-- Featured Products Section - moved up -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 flex-column flex-md-row">
                <div class="text-center text-md-start">
                    <h2 class="section-title mb-2">Featured Products</h2>
                    {{-- Subtitle removed as per request --}}
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('products.index') }}" class="text-decoration-none custome-text see-all-btn">
                        See All <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            @php
                $featuredProducts = \App\Models\Product::where('status', 'active')
                    ->where('is_featured', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            @endphp

            @if ($featuredProducts->count() > 0)
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-md-6 col-lg-3">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
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

    <!-- About Eterna Reads Section - moved down -->
    <section class="py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-3">
                    <div class="about-content">
                        <span class="custom-badge">{{ setting('home.focus_badge', 'Our Focus') }}</span>
                        <div class="row mt-2">
                            <div class="col-12 col-md-10 col-lg-8">
                                <h2 class="section-title mb-4">
                                    {{ setting('home.focus_heading', 'The future belongs to those who prepare for it today') }}
                                </h2>
                            </div>
                            <div class="col-12 col-md-8 col-lg-6">
                                <p class="lead mb-4">
                                    {{ setting('home.focus_subtitle', 'What are our pillars? What drives us?') }}
                                </p>
                            </div>
                        </div>

                        <!-- Updated focus items -->
                        <div class="about-stats row text-center justify-content-center">
                            <div class="col-md-12 d-flex ">
                                <div class="stat-item mx-2">
                                    <p class="focus-btn focus-active text-center px-3">
                                        {{ setting('about.value_1_title', 'Curriculum Precision') }}</p>
                                </div>
                                <div class="stat-item mx-2">
                                    <p class="focus-btn text-center px-3">
                                        {{ setting('about.value_2_title', 'Teacher Empowerment') }}</p>
                                </div>
                                <div class="stat-item mx-2">
                                    <p class="focus-btn text-center px-3 ">
                                        {{ setting('about.value_3_title', 'Learner Achievement') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('about') }}" class="btn custom-btn btn-lg mt-4">
                            {{ setting('home.focus_button_text', 'Learn More About Us') }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    @php
                        $focusImage = setting('home.focus_image');
                        $imageUrl = $focusImage ? asset('storage/' . $focusImage) : asset('images/about.jpg');
                    @endphp
                    <img src="{{ $imageUrl }}" alt="Our Focus" class="img-fluid w-100 rounded-3 shadow-lg"
                        style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    {{-- @php
        $featuredReviews = \App\Models\Review::featured()->active()->ordered()->limit(3)->get();
    @endphp

    @if ($featuredReviews->count() > 0)
        <section class="py-5" style="background: var(--light-bg);">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title mb-3">What Our Readers Say</h2>
                    <p class="section-subtitle">Join our community of satisfied book lovers</p>
                </div>

                <div class="row g-4">
                    @foreach ($featuredReviews as $review)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    @if ($review->avatar)
                                        <div class="mb-3">
                                            <img src="{{ $review->avatar_url }}" alt="{{ $review->name }}"
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
                                    @if ($review->title)
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
                    <p class="mb-0">Subscribe to our newsletter for book recommendations, exclusive offers, and literary
                        news.</p>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Enter your email address">
                        <button class="btn btn-light">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

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
                        updateCartCount();

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
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qualification = document.getElementById('qualification');
            const subject = document.getElementById('subject');
            const examBoard = document.getElementById('exam_board');
            const viewButton = document.getElementById('view-course-btn');

            function checkSelections() {
                const isValid =
                    qualification.value !== '' &&
                    subject.value !== '' &&
                    examBoard.value !== '';
                viewButton.disabled = !isValid;
            }

            // Attach event listeners
            qualification.addEventListener('change', checkSelections);
            subject.addEventListener('change', checkSelections);
            examBoard.addEventListener('change', checkSelections);
        });
    </script>

@endsection
