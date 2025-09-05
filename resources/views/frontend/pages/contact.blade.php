@extends('frontend.layouts.app')

@section('title', 'Contact Us - ' . setting('store.name', config('app.name')))

@section('content')
    <!-- Page Header -->
    <section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-white display-4 fw-bold mb-3">Contact Us</h1>
                    <p class="text-white lead mb-0">We'd love to hear from you. Get in touch with our team at <strong>{{ setting('store.name', config('app.name')) }}</strong>.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-info py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-geo-alt-fill fs-1" style="color: var(--primary-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Visit Our Store</h4>
                        <p class="text-muted mb-0">
                            {!! nl2br(e(setting('store.address', '123 Book Street\nLiterary City, LC 12345\nUnited States'))) !!}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-telephone-fill fs-1" style="color: var(--secondary-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Call Us</h4>
                        <p class="text-muted mb-0">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('store.phone', '+1 (555) 123-4567')) }}" class="text-decoration-none">{{ setting('store.phone', '+1 (555) 123-4567') }}</a><br>
                            {{ setting('store.hours_weekdays', 'Monday - Friday: 9AM - 6PM') }}<br>
                            {{ setting('store.hours_saturday', 'Saturday: 10AM - 4PM') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-envelope-fill fs-1" style="color: var(--info-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Email Us</h4>
                        <p class="text-muted mb-0">
                            @php
                                $emails = array_filter(array_map('trim', explode(',', setting('store.email', 'hello@eternareads.com'))));
                            @endphp
                            @foreach($emails as $email)
                                <a href="mailto:{{ $email }}" class="text-decoration-none">{{ $email }}</a><br>
                            @endforeach
                            We'll respond within 24 hours
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col text-center">
                    @if(setting('store.facebook'))
                        <a href="{{ setting('store.facebook') }}" target="_blank" class="me-2"><i class="bi bi-facebook fs-4"></i></a>
                    @endif
                    @if(setting('store.instagram'))
                        <a href="{{ setting('store.instagram') }}" target="_blank" class="me-2"><i class="bi bi-instagram fs-4"></i></a>
                    @endif
                    @if(setting('store.twitter'))
                        <a href="{{ setting('store.twitter') }}" target="_blank" class="me-2"><i class="bi bi-twitter fs-4"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    @include('frontend.partials.contact-form')

    <!-- Map Section -->
    <section class="map-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Find Our Store</h2>
                <p class="section-subtitle">Visit us in person to experience our curated collection</p>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-0">
                            <!-- Google Maps Embed -->
                            <div class="ratio ratio-21x9">
                                <iframe
                                    src="{{ setting('store.map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007%2C%20USA!5e0!3m2!1sen!2sin!4v1640995200000!5m2!1sen!2sin') }}"
                                    style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Preview Section (Dynamic) -->
    @if(isset($faqPreview) && $faqPreview->count())
    <section class="faq-preview py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title mb-3">Frequently Asked Questions</h2>
                    <p class="section-subtitle mb-4">Find quick answers to common questions</p>

                    <div class="row g-4">
                        @foreach($faqPreview as $faq)
                        <div class="col-md-6">
                            <div class="faq-item text-start">
                                <h5 class="mb-2">
                                    <i class="bi bi-question-circle me-2" style="color: var(--primary-color);"></i>
                                    {{ $faq->question }}
                                </h5>
                                <p class="text-muted small">{!! $faq->answer !!}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('faq') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-question-circle me-2"></i>View All FAQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Store Hours Section -->
    <section class="store-hours py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center mb-5">
                        <h2 class="section-title mb-3">Store Hours</h2>
                        <p class="section-subtitle">Plan your visit to our physical store</p>
                    </div>

                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mb-3">Monday - Friday</h5>
                                    <p class="text-muted">{{ setting('store.hours_weekdays', '9:00 AM - 6:00 PM') }}</p>

                                    <h5 class="mb-3">Saturday</h5>
                                    <p class="text-muted">{{ setting('store.hours_saturday', '10:00 AM - 4:00 PM') }}</p>

                                    <h5 class="mb-3">Sunday</h5>
                                    <p class="text-muted">{{ setting('store.hours_sunday', 'Closed') }}</p>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-3">Holiday Hours</h5>
                                    <p class="text-muted small">
                                        {{ setting('store.hours_holiday', 'We may have modified hours during holidays. Please call ahead or check our social media for updates.') }}
                                    </p>

                                    <h5 class="mb-3">Special Events</h5>
                                    <p class="text-muted small">
                                        {{ setting('store.hours_special', 'We host book clubs, author readings, and other literary events. Check our blog for upcoming events.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

 

    <!-- Call to Action -->
    <section class="cta-section py-5"
        style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mb-3">{{ setting('contact.cta_heading', 'Still Have Questions?') }}</h2>
                    <p class="text-white mb-4">{{ setting('contact.cta_subheading', 'Our friendly team is here to help you find the perfect book or answer any questions you might have.') }}</p>
                    <div class="cta-buttons">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact.cta_phone', '+1 (555) 123-4567')) }}" class="btn btn-light btn-lg me-3 mb-2">
                            <i class="bi bi-telephone me-2"></i>{{ setting('contact.cta_phone_text', 'Call Us Now') }}
                        </a>
                        <a href="mailto:{{ setting('contact.cta_email', 'hello@eternareads.com') }}" class="btn btn-outline-light btn-lg mb-2">
                            <i class="bi bi-envelope me-2"></i>{{ setting('contact.cta_email_text', 'Email Us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
