@extends('frontend.layouts.app')

@section('title', 'Review ' . $product->name . ' - EMS')

@section('content')
    <!-- Page Header -->
    <section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-white display-4 fw-bold mb-3">Review Product</h1>
                    <p class="text-white lead mb-0">Share your thoughts about {{ $product->name }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Form Section -->
    <section class="review-form py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
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

                    <!-- Product Preview -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $product->name }}</h5>
                                    <p class="text-muted small mb-0">{{ $product->category->name ?? 'Product' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-4">Your Review</h3>
                            <p class="text-muted mb-4">
                                Your feedback helps other users make informed decisions. Thank you for sharing your experience!
                            </p>

                            <form action="{{ route('products.review.store', $product->id) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" {{ old('rating', 5) == 5 ? 'checked' : '' }} />
                                        <label for="star5" title="5 stars"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }} />
                                        <label for="star4" title="4 stars"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }} />
                                        <label for="star3" title="3 stars"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }} />
                                        <label for="star2" title="2 stars"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }} />
                                        <label for="star1" title="1 star"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" 
                                              name="comment" 
                                              rows="6" 
                                              maxlength="1000"
                                              required>{{ old('comment') }}</textarea>
                                    <div class="form-text">Maximum 1000 characters</div>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-2"></i>Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .star-rating {
            direction: rtl;
            display: inline-flex;
            font-size: 2rem;
            gap: 5px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
    </style>
@endsection
