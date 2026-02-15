@extends('frontend.layouts.app')

{{-- @section('title', 'Eterna Reads - Your Literary Haven') --}}
<style>
    #courses {
        scroll-margin-top: 100px;
        /* Adjust the value as needed */
    }

    /* Brand-colored slider controls */
    #heroCarousel .carousel-control-prev-icon,
    #heroCarousel .carousel-control-next-icon {
        background-image: none;
        width: 2.5rem;
        height: 2.5rem;
        position: relative;
    }

    #heroCarousel .carousel-control-prev-icon::before,
    #heroCarousel .carousel-control-next-icon::before {
        content: '';
        display: block;
        width: 100%;
        height: 100%;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-position: center;
        background-color: transparent;
    }

    #heroCarousel .carousel-control-prev-icon::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2319390b'%3E%3Cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
    }

    #heroCarousel .carousel-control-next-icon::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2319390b'%3E%3Cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    }
</style>

@section('content')
    <!-- Hero Section with Carousel -->
    @php
        $sliders = \App\Models\Slider::active()->ordered()->get();
        $essays = \App\Models\Essay::latest()->limit(4)->get();

    @endphp

    @if ($sliders->count() > 0)
        <section class="hero-section pt-0" style="height: 60vh">
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
        $featuredReviews = \App\Models\Review::featured()
            ->active()
            ->approved()
            ->orderBy('sort_order', 'asc')
            ->get();
            
          
    @endphp

    {{-- @if ($featuredReviews->count() > 0 && setting('home.show_reviews', true)) --}}
    <section class="reviews-carousel-section py-5"
        style="
            background: linear-gradient(135deg, #f8f9ff 0%, var(--light-bg) 50%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        ">
        <!-- Decorative Background Elements -->
        <div
            style="
                position: absolute;
                top: -100px;
                right: -100px;
                width: 300px;
                height: 300px;
                background: rgba(var(--primary-color-rgb, 59, 130, 246), 0.08);
                border-radius: 50%;
                z-index: 1;
            ">
        </div>
        <div
            style="
                position: absolute;
                bottom: -80px;
                left: -80px;
                width: 250px;
                height: 250px;
                background: rgba(var(--primary-color-rgb, 59, 130, 246), 0.06);
                border-radius: 50%;
                z-index: 1;
            ">
        </div>

        <div class="container" style="position: relative; z-index: 2;">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <div class="mb-3">
                    <span class="badge"
                        style="
                            background: linear-gradient(135deg, #19390b, #0d1f06);
                            font-size: 0.85rem;
                            padding: 0.6rem 1.2rem;
                            border-radius: 50px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        ">
                        <i class="bi bi-star-fill me-2"></i>{{ setting('home.reviews_badge_text', 'Tell us what you think') }}
                    </span>
                </div>
                <h2 class="section-title mb-3"
                    style="
                        color: #19390b;
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
                    <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-pause="hover"
                        data-bs-interval="5000">
                        @php
                            $reviewChunks = $featuredReviews->chunk(3);
                        @endphp
                        <div class="carousel-inner">
                            @foreach ($reviewChunks as $chunkIndex => $chunk)
                                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="row g-4">
                                        @foreach ($chunk as $review)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="review-card"
                                                    style="
                                                        background: #ffffff;
                                                        border: 1px solid #e5e7eb;
                                                        border-radius: 16px;
                                                        padding: 24px 20px;
                                                        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
                                                        height: 380px;
                                                        display: flex;
                                                        flex-direction: column;
                                                        gap: 14px;
                                                    ">
                                                    <div
                                                        style="display: flex; gap: 8px; font-size: 1.1rem; color: #19390b; flex-shrink: 0;">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                                                                style="color: #19390b;"></i>
                                                        @endfor
                                                    </div>

                                                    <h5
                                                        style="font-weight: 700; color: #111827; font-size: 1.1rem; line-height: 1.5; margin: 0; flex-shrink: 0;">
                                                        @if (!empty($review->heading))
                                                            {{ $review->heading }}
                                                        @else
                                                            {{ $review->name ?: 'Happy learner' }} ({{ $review->title }})
                                                        @endif
                                                    </h5>

                                                    <p
                                                        style="color: #374151; font-size: 0.98rem; line-height: 1.7; margin: 0; flex: 1; overflow: hidden;">
                                                        {{ Str::limit($review->content, 180) }}
                                                    </p>
                                                    @if (Str::length($review->content) > 180)
                                                        <button type="button"
                                                            class="btn btn-link p-0 align-self-start"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reviewContentModal-{{ $review->id }}"
                                                            style="font-size: 0.9rem; font-weight: 600; color: #19390b; text-decoration: none;">
                                                            Show more
                                                        </button>
                                                    @endif

                                                    <div style="border-top: 1px solid #e5e7eb; margin: 6px 0; flex-shrink: 0;"></div>

                                                    <div
                                                        style="display: flex; align-items: center; gap: 10px; flex-shrink: 0; min-height: 48px;">
                                                        @if ($review->country_flag_url)
                                                            <img src="{{ $review->country_flag_url }}" alt="Country"
                                                                style="width: 28px; height: 20px; object-fit: cover; border-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,0.12);">
                                                        @endif
                                                        <div style="flex: 1;">
                                                            <div style="font-weight: 700; color: #0f172a;">
                                                                {{ $review->name }}({{ $review->title }} )</div>
                                                            {{-- @if ($review->title)
                                                                <div
                                                                    style="color: #4b5563; font-weight: 600; font-size: 0.95rem;">
                                                                    {{ $review->title }}</div>
                                                            @endif --}}
                                                        </div>
                                                    </div>
                                                    @if (Str::length($review->content) > 180)
                                                        <div class="modal fade" id="reviewContentModal-{{ $review->id }}"
                                                            tabindex="-1" aria-labelledby="reviewContentModalLabel-{{ $review->id }}"
                                                            aria-hidden="true" data-bs-backdrop="false"
                                                            data-bs-keyboard="false">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="reviewContentModalLabel-{{ $review->id }}">
                                                                            {{ $review->heading ?: ($review->name ?: 'Happy learner') }}
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p style="color: #374151; font-size: 1rem; line-height: 1.8; margin: 0;">
                                                                            {{ $review->content }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary text-white"
                                                                            data-bs-dismiss="modal" style="background:linear-gradient(135deg, #19390b, #0d1f06)">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Carousel Indicators at Bottom -->
                        @if ($reviewChunks->count() > 1)
                            <div
                                style="
                                    text-align: center;
                                    margin-top: 30px;
                                    display: flex;
                                    gap: 10px;
                                    justify-content: center;
                                    flex-wrap: wrap;
                                    padding-bottom: 10px;
                                ">
                                @foreach ($reviewChunks as $index => $chunk)
                                    <button type="button" data-bs-target="#reviewsCarousel"
                                        data-bs-slide-to="{{ $index }}"
                                        class="{{ $index === 0 ? 'active' : '' }} review-indicator"
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
                @if (Auth::user()->hasActiveSubscription())
                    <div class="text-center mt-5">
                        <a href="#"
                            class="btn btn-lg review-cta-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#reviewModal"
                            style="
                                background: linear-gradient(135deg, #19390b, #0d1f06);
                                border: none;
                                color: white;
                                padding: 12px 34px;
                                border-radius: 40px;
                                font-weight: 700;
                                font-size: 12px;
                                box-shadow: 0 6px 18px rgba(0,0,0,0.12);
                                transition: all 0.25s ease;
                                text-transform: uppercase;
                                letter-spacing: 0.4px;
                            ">
                            <i class="bi bi-pencil-square me-2"></i>Share Your Experience
                        </a>
                    </div>

                    <!-- Review Modal -->
                    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
                        aria-hidden="true" data-bs-backdrop="false">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content review-modal">
                                <div class="modal-header review-modal__header">
                                    <div>
                                        {{-- <p class="review-modal__eyebrow">Share your story</p> --}}
                                        <h5 class="modal-title review-modal__title" id="reviewModalLabel">Submit a Review</h5>
                                    </div>
                                    <button type="button" class="btn-close review-modal__close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 p-md-5 review-modal__body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="bi bi-check-circle me-2"></i>
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    {{-- <div class="review-modal__intro mb-4">
                                        <div class="review-modal__icon">
                                            <i class="bi bi-chat-quote-fill"></i>
                                        </div>
                                        <p>
                                            Thank you for taking the time to share your feedback! Your review will be reviewed by
                                            our team before being published.
                                        </p>
                                    </div> --}}

                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf

                                        <div class="mb-4">
                                            <label for="review_name" class="form-label">Your Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="review_name" name="name"
                                                value="{{ old('name', Auth::user()->name) }}" required readonly>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="review_country" class="form-label">Country <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $reviewCountries = \App\Models\Country::listCountries();
                                                $userCountry = old('country_code') ?? (Auth::user()?->country ?? '');
                                            @endphp
                                            <select id="review_country" name="country_code"
                                                class="form-select @error('country_code') is-invalid @enderror" required>
                                                <option value="" disabled
                                                    {{ $userCountry == '' ? 'selected' : '' }}>
                                                    Select your country
                                                </option>
                                                @foreach ($reviewCountries as $code => $name)
                                                    <option value="{{ $code }}"
                                                        {{ $userCountry == $code ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="review_title" class="form-label">Role <span
                                                    class="text-danger">*</span></label>
                                            @php
                                                $reviewRoles = [
                                                    'Student',
                                                    'Teacher',
                                                    'Head of Department',
                                                    'Principal',
                                                    'Tutor',
                                                    'Parent',
                                                    'Administrator',
                                                    'Other',
                                                ];
                                            @endphp
                                            <select id="review_title" name="title"
                                                class="form-select @error('title') is-invalid @enderror" required>
                                                <option value="" disabled
                                                    {{ old('title') == '' ? 'selected' : '' }}>
                                                    Select your role
                                                </option>
                                                @foreach ($reviewRoles as $role)
                                                    <option value="{{ $role }}"
                                                        {{ old('title') == $role ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Rating <span
                                                    class="text-danger">*</span></label>
                                            <div class="star-rating review-modal__stars">
                                                <input type="radio" id="home_star5" name="rating" value="5"
                                                    {{ old('rating', 5) == 5 ? 'checked' : '' }} />
                                                <label for="home_star5" title="5 stars"><i
                                                        class="bi bi-star-fill"></i></label>

                                                <input type="radio" id="home_star4" name="rating" value="4"
                                                    {{ old('rating') == 4 ? 'checked' : '' }} />
                                                <label for="home_star4" title="4 stars"><i
                                                        class="bi bi-star-fill"></i></label>

                                                <input type="radio" id="home_star3" name="rating" value="3"
                                                    {{ old('rating') == 3 ? 'checked' : '' }} />
                                                <label for="home_star3" title="3 stars"><i
                                                        class="bi bi-star-fill"></i></label>

                                                <input type="radio" id="home_star2" name="rating" value="2"
                                                    {{ old('rating') == 2 ? 'checked' : '' }} />
                                                <label for="home_star2" title="2 stars"><i
                                                        class="bi bi-star-fill"></i></label>

                                                <input type="radio" id="home_star1" name="rating" value="1"
                                                    {{ old('rating') == 1 ? 'checked' : '' }} />
                                                <label for="home_star1" title="1 star"><i
                                                        class="bi bi-star-fill"></i></label>
                                            </div>
                                            @error('rating')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="review_comment" class="form-label">Your Review <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control review-modal__textarea @error('comment') is-invalid @enderror"
                                                id="review_comment" name="comment" rows="6" maxlength="1000" required>{{ old('comment') }}</textarea>
                                            <div class="form-text">Maximum 1000 characters</div>
                                            @error('comment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="button" class="btn btn-outline-secondary review-modal__btn-ghost"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn review-modal__btn-primary">
                                                <i class="bi bi-send me-2"></i>Submit Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



            @endauth
        </div>

        <style>
            .review-card {
                animation: slideIn 0.5s ease;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .review-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08) !important;
            }

            .carousel-item.active .review-card {
                animation: slideIn 0.5s ease;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .review-indicator {
                width: 14px;
                height: 14px;
                border-radius: 50%;
                border: 2px solid #ddd;
                background-color: transparent;
                cursor: pointer;
                transition: all 0.4s ease;
                box-shadow: none;
            }

            .review-indicator.active {
                border-color: #19390b;
                background-color: #19390b;
                box-shadow: 0 0 12px rgba(25, 57, 11, 0.45);
            }

            .review-indicator:hover:not(.active) {
                border-color: #19390b;
                background-color: rgba(25, 57, 11, 0.2);
            }

            @media (max-width: 768px) {
                .review-card {
                    padding: 20px 16px;
                    height: 360px !important;
                }

                h2.section-title {
                    font-size: 1.8rem !important;
                }

                .review-indicator {
                    width: 12px !important;
                    height: 12px !important;
                }
            }

            .modal .star-rating {
                direction: rtl;
                display: inline-flex;
                font-size: 2rem;
                gap: 5px;
            }

            .modal .star-rating input[type="radio"] {
                display: none;
            }

            .modal .star-rating label {
                color: #ddd;
                cursor: pointer;
                transition: color 0.2s;
            }

            .modal .star-rating label:hover,
            .modal .star-rating label:hover~label,
            .modal .star-rating input[type="radio"]:checked~label {
                color: #ffc107;
            }

            .review-modal {
                border: 0;
                border-radius: 22px;
                overflow: hidden;
                background: radial-gradient(circle at top right, rgba(25, 57, 11, 0.08), transparent 55%),
                    #ffffff;
                box-shadow: 0 24px 80px rgba(15, 23, 42, 0.25);
            }

            .modal {
                z-index: 3000 !important;
                position: fixed !important;
            }

            .modal-backdrop {
                z-index: 2990 !important;
            }

            .modal-open .navbar.sticky-top {
                z-index: 1000 !important;
            }

            .review-modal__header {
                border: 0;
                padding: 28px 32px 16px;
                background: linear-gradient(135deg, rgba(25, 57, 11, 0.08), rgba(13, 31, 6, 0.03));
            }

            .review-modal__eyebrow {
                margin: 0 0 6px;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: #6b7280;
            }

            .review-modal__title {
                font-size: 1.6rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }

            .review-modal__close {
                border-radius: 999px;
                border: 1px solid rgba(15, 23, 42, 0.12);
                background: #ffffff;
                padding: 8px;
            }

            .review-modal__body {
                padding-top: 8px;
            }

            .review-modal__intro {
                display: flex;
                gap: 14px;
                align-items: flex-start;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                padding: 16px 18px;
                border-radius: 14px;
                color: #475569;
                font-weight: 500;
            }

            .review-modal__icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                background: rgba(25, 57, 11, 0.12);
                color: #19390b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .review-modal__textarea {
                min-height: 150px;
                resize: vertical;
                border-radius: 12px;
            }

            .review-modal__stars {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                padding: 10px 14px;
                border-radius: 12px;
            }

            .review-modal__btn-primary {
                background: linear-gradient(135deg, #19390b, #0d1f06);
                color: #fff;
                border: 0;
                font-weight: 700;
                padding: 10px 20px;
                border-radius: 12px;
            }

            .review-modal__btn-ghost {
                border-radius: 12px;
            }

            @media (max-width: 768px) {
                .review-modal__header {
                    padding: 22px 20px 12px;
                }

                .review-modal__title {
                    font-size: 1.35rem;
                }

                .review-modal__intro {
                    flex-direction: column;
                }
            }
        </style>
    </section>
    {{-- @endif --}}

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
                    ->orderBy('sort_order', 'desc')
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

            if (qualification && subject && examBoard && viewButton) {
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
            }

            // Handle review carousel indicator updates
            const reviewsCarousel = document.getElementById('reviewsCarousel');
            if (reviewsCarousel) {
                reviewsCarousel.addEventListener('slid.bs.carousel', function(event) {
                    const indicators = document.querySelectorAll('.review-indicator');
                    indicators.forEach((indicator, index) => {
                        if (index === event.to) {
                            indicator.classList.add('active');
                            indicator.setAttribute('aria-current', 'true');
                        } else {
                            indicator.classList.remove('active');
                            indicator.setAttribute('aria-current', 'false');
                        }
                    });
                });
            }

            const reviewContentModals = document.querySelectorAll('[id^="reviewContentModal-"]');
            if (reviewsCarousel && reviewContentModals.length && window.bootstrap) {
                const carouselInstance = bootstrap.Carousel.getOrCreateInstance(reviewsCarousel);
                reviewContentModals.forEach((modalEl) => {
                    modalEl.addEventListener('show.bs.modal', function() {
                        carouselInstance.pause();
                    });
                    modalEl.addEventListener('hidden.bs.modal', function() {
                        carouselInstance.cycle();
                    });
                });
            }

            const shouldOpenReviewModal = @json($errors->any() || session('success') || session('error'));
            if (shouldOpenReviewModal) {
                const reviewModalEl = document.getElementById('reviewModal');
                if (reviewModalEl && window.bootstrap) {
                    const reviewModal = new bootstrap.Modal(reviewModalEl);
                    reviewModal.show();
                }
            }

            const reviewModalEl = document.getElementById('reviewModal');
            if (reviewModalEl && reviewModalEl.parentElement !== document.body) {
                document.body.appendChild(reviewModalEl);
            }
        });
    </script>

@endsection
