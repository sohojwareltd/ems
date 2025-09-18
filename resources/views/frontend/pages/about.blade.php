@extends('frontend.layouts.app')

@section('title', 'About Us - EMS')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="text-white display-4 fw-bold mb-3">About Economics Made Simple (EMS)</h1>
                <p class="text-white lead mb-0">Our story, mission, and the team behind your literary haven</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="our-story py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="story-content">
                    <h2 class="section-title mb-4">Our Story</h2>
                    <p class="lead mb-4">
                        EMS was born from a simple yet powerful belief: that every person deserves access to 
                        exceptional books that inspire, educate, and entertain. What started as a small local bookstore 
                        has grown into a beloved literary destination for book lovers everywhere.
                    </p>
                    <p class="mb-4">
                        Founded in 2020 by a group of passionate bibliophiles, we began with a modest collection of 
                        carefully curated books. Our founders shared a vision of creating more than just a place to 
                        buy books – they wanted to build a community where readers could discover new worlds, share 
                        their love for literature, and find the perfect book for every moment.
                    </p>
                    {{-- <p class="mb-4">
                        Today, EMS continues to grow while staying true to our roots. We've expanded our 
                        offerings to include audiobooks, curated gift boxes, and literary merchandise, but our 
                        commitment to quality, community, and the love of reading remains unchanged.
                    </p> --}}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="story-image text-center">
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt=" Story" 
                         class="img-fluid rounded-3 shadow-lg" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="mission-values py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5">
                <div class="mission-content">
                    <h2 class="section-title mb-4">Our Mission</h2>
                    <p class="lead mb-4">
                        To inspire a lifelong love of reading by providing access to exceptional books and creating 
                        meaningful connections within our literary community.
                    </p>
                    <p class="mb-4">
                        We believe that books have the power to transform lives, broaden perspectives, and bring 
                        people together. Our mission is to be the bridge between readers and the stories that will 
                        change their lives.
                    </p>
                    <div class="mission-stats row text-center">
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold" style="color: var(--primary-color);">1000+</h3>
                                <p class="text-muted">Books Curated</p>
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
                </div>
            </div>
            <div class="col-lg-6">
                <div class="values-content">
                    <h2 class="section-title mb-4">Our Values</h2>
                    <div class="values-list">
                        <div class="value-item d-flex align-items-start mb-4">
                            <div class="value-icon me-3">
                                <i class="bi bi-heart-fill fs-4" style="color: var(--primary-color);"></i>
                            </div>
                            <div class="value-content">
                                <h4 class="h5 mb-2">Passion for Literature</h4>
                                <p class="text-muted mb-0">We're driven by our love for books and storytelling, and we share that passion with every customer.</p>
                            </div>
                        </div>
                        <div class="value-item d-flex align-items-start mb-4">
                            <div class="value-icon me-3">
                                <i class="bi bi-people-fill fs-4" style="color: var(--secondary-color);"></i>
                            </div>
                            <div class="value-content">
                                <h4 class="h5 mb-2">Community First</h4>
                                <p class="text-muted mb-0">We believe in building strong relationships with our customers and creating a welcoming literary community.</p>
                            </div>
                        </div>
                        <div class="value-item d-flex align-items-start mb-4">
                            <div class="value-icon me-3">
                                <i class="bi bi-award-fill fs-4" style="color: var(--success-color);"></i>
                            </div>
                            <div class="value-content">
                                <h4 class="h5 mb-2">Quality Curation</h4>
                                <p class="text-muted mb-0">Every book in our collection is carefully selected to ensure the highest quality and relevance for our readers.</p>
                            </div>
                        </div>
                        <div class="value-item d-flex align-items-start">
                            <div class="value-icon me-3">
                                <i class="bi bi-lightbulb-fill fs-4" style="color: var(--warning-color);"></i>
                            </div>
                            <div class="value-content">
                                <h4 class="h5 mb-2">Innovation</h4>
                                <p class="text-muted mb-0">We continuously evolve to meet the changing needs of our readers while staying true to our core mission.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">Meet Our Team</h2>
            <p class="section-subtitle">The passionate individuals behind EMS</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="team-member text-center">
                    <div class="team-photo mb-3">
                        <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                             alt="Sarah Johnson" 
                             class="img-fluid rounded-circle shadow" 
                             style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <h4 class="h5 mb-2">Sarah Johnson</h4>
                    <p class="text-muted mb-2">Founder & CEO</p>
                    <p class="small text-muted">
                        A lifelong book lover with 15+ years in the publishing industry. Sarah's vision drives our mission to connect readers with exceptional stories.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-member text-center">
                    <div class="team-photo mb-3">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Michael Chen" 
                             class="img-fluid rounded-circle shadow" 
                             style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <h4 class="h5 mb-2">Michael Chen</h4>
                    <p class="text-muted mb-2">Head of Curation</p>
                    <p class="small text-muted">
                        With a Master's in Literature, Michael ensures every book in our collection meets our high standards for quality and relevance.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-member text-center">
                    <div class="team-photo mb-3">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Emily Rodriguez" 
                             class="img-fluid rounded-circle shadow" 
                             style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <h4 class="h5 mb-2">Emily Rodriguez</h4>
                    <p class="text-muted mb-2">Customer Experience Manager</p>
                    <p class="small text-muted">
                        Emily ensures every customer interaction is exceptional, from personalized recommendations to seamless shopping experiences.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">Why Choose EMS?</h2>
            <p class="section-subtitle">What makes us different from other bookstores</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-search fs-1" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="h5 mb-3">Curated Selection</h4>
                    <p class="text-muted">Every book is handpicked by our expert team to ensure quality and relevance for our readers.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-truck fs-1" style="color: var(--secondary-color);"></i>
                    </div>
                    <h4 class="h5 mb-3">Fast Delivery</h4>
                    <p class="text-muted">Quick and reliable shipping to get your books to you as soon as possible.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-headset fs-1" style="color: var(--info-color);"></i>
                    </div>
                    <h4 class="h5 mb-3">Expert Support</h4>
                    <p class="text-muted">Our knowledgeable team is always ready to help you find the perfect book.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-heart fs-1" style="color: var(--danger-color);"></i>
                    </div>
                    <h4 class="h5 mb-3">Community Focus</h4>
                    <p class="text-muted">We're more than a bookstore – we're a community of passionate readers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="text-white mb-3">Ready to Start Your Reading Journey?</h2>
                <p class="text-white mb-4">Explore our collection and discover your next favorite book today.</p>
                <div class="cta-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3 mb-2">
                        <i class="bi bi-book me-2"></i>Browse Books
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg mb-2">
                        <i class="bi bi-envelope me-2"></i>Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 