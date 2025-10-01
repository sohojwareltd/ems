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

    <!-- Quick Navigation Section -->
    <section class="py-5" id="courses">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 flex-column flex-md-row">
                <div class="text-center text-md-start">
                    <h2 class="section-title mb-2">Select Your Course</h2>
                    {{-- <p class="section-subtitle mb-0 w-50">Welcome to our extensive catalog of model essays, carefully
                        curated by
                        our
                        examiners. We are committed to providing you the best resources possible.</p> --}}
                </div>
                <div class="mt-3 mt-md-0">
                    {{-- <a href="{{ route('model.index') }}" class="text-decoration-none custome-text see-all-btn">See All <i
                            class="fa-solid fa-arrow-right"></i></a> --}}
                </div>
            </div>

            @php
                $subjects = \App\Models\Subject::orderBy('title')->get();
                $examBoards = \App\Models\Examboard::orderBy('title')->get();
                $qualifications = \App\Models\Qualification::orderBy('title')->get();
            @endphp
            <form action="{{ route('model.index') }}" method="get">
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="qualification">Qualification</label>
                        <select id="qualification" name="qualification" class="form-select">
                            <option value=""> Select Qualification</option>
                            @foreach ($qualifications as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                            {{-- <option value="gcse">GCSE</option> --}}

                            <!-- Add more stages if needed -->
                        </select>
                    </div>


                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="subject">Subject</label>
                        <select id="subject" name="subject" class="form-select">
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="exam_board">Exam Board</label>
                        <select id="exam_board" name="exam_board" class="form-select">
                            <option value="">Select Exam Board</option>
                            @foreach ($examBoards as $examBoard)
                                <option value="{{ $examBoard->id }}">{{ $examBoard->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center mt-2">
                        <button id="view-course-btn" type="submit" class="btn custom-btn mt-1 w-100" disabled>
                            View Course
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </section>

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
                        <span class="custom-badge">Our Focus</span>
                        <div class="row mt-2">
                            <div class="col-12 col-md-10 col-lg-8">
                                <h2 class="section-title mb-4">The future belongs to those who prepare for it today</h2>
                            </div>
                            <div class="col-12 col-md-8 col-lg-6">
                                <p class="lead mb-4">
                                    What are our pillars? What drives our decision-making? What does the future look like
                                    for EMS?
                                </p>
                            </div>
                        </div>

                        <!-- Updated focus items -->
                        <div class="about-stats row text-center justify-content-center">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="stat-item mx-2">
                                    <p class="focus-btn focus-active text-center">Maximising student outcomes</p>
                                </div>
                                <div class="stat-item mx-2">
                                    <p class="focus-btn text-center">Integrating education with technology</p>
                                </div>
                                <div class="stat-item mx-2">
                                    <p class="focus-btn text-center">Improving financial literacy</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('about') }}" class="btn custom-btn btn-lg mt-4">
                            Learn More About Us
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="{{ asset('images/about.jpg') }}" alt="About Eterna Reads"
                        class="img-fluid w-100 rounded-3 shadow-lg" style="max-height: 400px; object-fit: cover;">
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
