@extends('frontend.layouts.app')

@section('title', 'FAQ - Eterna Reads')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="text-white display-4 fw-bold mb-3">Frequently Asked Questions</h1>
                <p class="text-white lead mb-0">Find answers to common questions about our services and policies</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Navigation -->
@if($faqCategories->count() > 0)
<section class="faq-nav py-4" style="background: var(--light-bg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach($faqCategories as $category)
                        <a href="#{{ $category->slug }}" class="btn btn-outline-primary btn-sm">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- FAQ Content -->
<section class="faq-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                @forelse($faqCategories as $category)
                    <div id="{{ $category->slug }}" class="faq-section mb-5">
                        <h2 class="section-title mb-4">
                            @if($category->icon)
                                <i class="{{ $category->icon }} me-2" style="color: {{ $category->color ?? 'var(--primary-color)' }};"></i>
                            @endif
                            {{ $category->name }}
                        </h2>
                        
                        @if($category->description)
                            <p class="text-muted mb-4">{{ $category->description }}</p>
                        @endif
                        
                        @if($category->activeFaqItems->count() > 0)
                            <div class="accordion" id="{{ $category->slug }}Accordion">
                                @foreach($category->activeFaqItems as $index => $faqItem)
                                    <div class="accordion-item border-0 shadow-sm mb-3">
                                        <h3 class="accordion-header">
                                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $category->slug }}{{ $faqItem->id }}">
                                                {{ $faqItem->question }}
                                            </button>
                                        </h3>
                                        <div id="{{ $category->slug }}{{ $faqItem->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#{{ $category->slug }}Accordion">
                                            <div class="accordion-body">
                                                {!! $faqItem->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                No FAQ items available for this category yet.
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-question-circle fs-1 text-muted mb-3"></i>
                        <h3>No FAQ Categories Available</h3>
                        <p class="text-muted">FAQ content will be available soon.</p>
                    </div>
                @endforelse

                <!-- Privacy Policy Section -->
                <div id="privacy" class="faq-section mb-5">
                    <h2 class="section-title mb-4">
                        <i class="bi bi-shield-check me-2" style="color: var(--primary-color);"></i>
                        Privacy Policy
                    </h2>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="h5 mb-3">Information We Collect</h3>
                            <p class="mb-3">We collect information you provide directly to us, such as when you create an account, place an order, or contact us. This may include your name, email address, shipping address, and payment information.</p>
                            
                            <h3 class="h5 mb-3">How We Use Your Information</h3>
                            <p class="mb-3">We use the information we collect to process your orders, communicate with you about your orders, send you marketing materials (with your consent), and improve our services.</p>
                            
                            <h3 class="h5 mb-3">Information Sharing</h3>
                            <p class="mb-3">We do not sell, trade, or otherwise transfer your personal information to third parties except as described in our privacy policy or with your consent.</p>
                            
                            <h3 class="h5 mb-3">Data Security</h3>
                            <p class="mb-3">We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                            
                            <h3 class="h5 mb-3">Your Rights</h3>
                            <p class="mb-0">You have the right to access, update, or delete your personal information. You can also opt out of marketing communications at any time.</p>
                        </div>
                    </div>
                </div>

                <!-- Terms of Service Section -->
                <div id="terms" class="faq-section mb-5">
                    <h2 class="section-title mb-4">
                        <i class="bi bi-file-text me-2" style="color: var(--secondary-color);"></i>
                        Terms of Service
                    </h2>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="h5 mb-3">Acceptance of Terms</h3>
                            <p class="mb-3">By accessing and using our website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                            
                            <h3 class="h5 mb-3">Use License</h3>
                            <p class="mb-3">Permission is granted to temporarily download one copy of the materials on Eterna Reads's website for personal, non-commercial transitory viewing only.</p>
                            
                            <h3 class="h5 mb-3">Disclaimer</h3>
                            <p class="mb-3">The materials on Eterna Reads's website are provided on an 'as is' basis. Eterna Reads makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                            
                            <h3 class="h5 mb-3">Limitations</h3>
                            <p class="mb-3">In no event shall Eterna Reads or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Eterna Reads's website.</p>
                            
                            <h3 class="h5 mb-3">Revisions and Errata</h3>
                            <p class="mb-0">The materials appearing on Eterna Reads's website could include technical, typographical, or photographic errors. Eterna Reads does not warrant that any of the materials on its website are accurate, complete or current.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Contact Support Section -->
<section class="contact-support py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">Still Have Questions?</h2>
                <p class="section-subtitle mb-4">Our customer service team is here to help you find the answers you need.</p>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="support-option">
                            <i class="bi bi-envelope-fill fs-1 mb-3" style="color: var(--primary-color);"></i>
                            <h4 class="h5 mb-2">Email Us</h4>
                            <p class="text-muted small mb-2">Get a response within 24 hours</p>
                            <a href="mailto:support@eternareads.com" class="btn btn-outline-primary btn-sm">Send Email</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-option">
                            <i class="bi bi-telephone-fill fs-1 mb-3" style="color: var(--secondary-color);"></i>
                            <h4 class="h5 mb-2">Call Us</h4>
                            <p class="text-muted small mb-2">Speak with our team directly</p>
                            <a href="tel:+15551234567" class="btn btn-outline-primary btn-sm">Call Now</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-option">
                            <i class="bi bi-chat-fill fs-1 mb-3" style="color: var(--success-color);"></i>
                            <h4 class="h5 mb-2">Live Chat</h4>
                            <p class="text-muted small mb-2">Chat with us in real-time</p>
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm">Start Chat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 