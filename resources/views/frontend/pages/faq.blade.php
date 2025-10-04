@extends('frontend.layouts.app')

@section('title', 'FAQ - EMS')
<style>
    #privacy {
        scroll-margin-top: 120px;
        /* Adjust the value as needed */
    }
</style>

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
    @if ($faqCategories->count() > 0)
        <section class="faq-nav py-4" style="background: var(--light-bg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach ($faqCategories as $category)
                                <a href="#{{ $category->slug }}" class="btn custom-btn-outline">{{ $category->name }}</a>
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
                                @if ($category->icon)
                                    <i class="{{ $category->icon }} me-2"
                                        style="color: {{ $category->color ?? 'var(--primary-color)' }};"></i>
                                @endif
                                {{ $category->name }}
                            </h2>

                            @if ($category->description)
                                <p class="text-muted mb-4">{{ $category->description }}</p>
                            @endif

                            @if ($category->activeFaqItems->count() > 0)
                                <div class="accordion" id="{{ $category->slug }}Accordion">
                                    @foreach ($category->activeFaqItems as $index => $faqItem)
                                        <div class="accordion-item border-0 shadow-sm mb-3">
                                            <h3 class="accordion-header">
                                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ $category->slug }}{{ $faqItem->id }}">
                                                    {{ $faqItem->question }}
                                                </button>
                                            </h3>
                                            <div id="{{ $category->slug }}{{ $faqItem->id }}"
                                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                data-bs-parent="#{{ $category->slug }}Accordion">
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
                            Privacy & Cookies Policy
                        </h2>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 mb-3">Who We Are</h3>
                                <p class="mb-3">ECONOMICS MADE SIMPLE LTD (“EMS”, “we”, “our”, “us”) is a UK-registered
                                    limited company.
                                    Data Controller: ECONOMICS MADE SIMPLE LTD. Contact: <a
                                        href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a>.</p>

                                <h3 class="h5 mb-3">Information We Collect</h3>
                                <p class="mb-3">We collect information you provide directly, automatically from your
                                    device, and via payment processors. This includes:</p>
                                <ul class="mb-3">
                                    <li>Identity: name, country, (optional) date of birth</li>
                                    <li>Contact: email, phone</li>
                                    <li>Account: username/email, hashed password, login history, settings</li>
                                    <li>Transaction: purchases, plan, timestamps, last 4 digits of card</li>
                                    <li>Technical: IP, device, browser, cookies, analytics</li>
                                    <li>Communications: emails, support messages, preferences</li>
                                </ul>

                                <h3 class="h5 mb-3">How We Use Your Information</h3>
                                <p class="mb-3">We process data based on UK GDPR lawful bases:</p>
                                <ul class="mb-3">
                                    <li>Account management, digital content delivery, support – Contract necessity</li>
                                    <li>Payments, tax records – Legal obligation</li>
                                    <li>Security, fraud prevention, service maintenance – Legitimate interests</li>
                                    <li>Analytics – Consent via cookies</li>
                                    <li>Marketing – Consent (or soft opt-in for existing customers)</li>
                                </ul>

                                <h3 class="h5 mb-3">Cookies & Tracking</h3>
                                <p class="mb-3">We use cookies to enhance the site:</p>
                                <ul class="mb-3">
                                    <li>Essential – required for login, checkout, security (always on)</li>
                                    <li>Analytics – understand usage (consent required)</li>
                                    <li>Marketing – improve ads (consent required)</li>
                                </ul>
                                <p class="mb-3">Non-essential cookies are set only after you click “Accept”. You can
                                    change your choice anytime via the site footer or your browser.</p>

                                <h3 class="h5 mb-3">Information Sharing</h3>
                                <p class="mb-3">We do not sell your data. Trusted processors include:</p>
                                <ul class="mb-3">
                                    <li>Payments: Stripe, PayPal</li>
                                    <li>Hosting/analytics: Google services</li>
                                    <li>Email (future): Mailchimp</li>
                                    <li>Contracted developers/hosting providers under confidentiality agreements</li>
                                </ul>

                                <h3 class="h5 mb-3">Data Security</h3>
                                <p class="mb-3">We use technical and organisational measures: HTTPS/TLS, hashed passwords,
                                    role-based access, encrypted backups, 2FA, and regular security reviews.</p>

                                <h3 class="h5 mb-3">Data Retention</h3>
                                <p class="mb-3">We keep your data only as long as necessary:</p>
                                <ul class="mb-3">
                                    <li>Accounts: while active; deleted within 12 months if inactive</li>
                                    <li>Financial records: 6 years (HMRC requirement)</li>
                                    <li>Support messages: up to 24 months</li>
                                    <li>Marketing lists: until unsubscribe or pruned</li>
                                    <li>Cookies: per tool duration</li>
                                </ul>

                                <h3 class="h5 mb-3">Your Rights</h3>
                                <p class="mb-3">You may access, update, or delete your data, restrict processing, object
                                    to marketing, request data portability, and withdraw consent. Complaints can be made to
                                    the UK ICO.</p>

                                <h3 class="h5 mb-3">Children & Young People</h3>
                                <p class="mb-3">Services are for teachers, parents, and older students. Under 18 should
                                    use EMS with supervision. We do not knowingly collect data from under 13 without
                                    consent.</p>

                                <h3 class="h5 mb-3">Contact & Complaints</h3>
                                <p class="mb-3">Email: <a
                                        href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a><br>
                                    Complaints: UK ICO – <a href="https://www.ico.org.uk" target="_blank">www.ico.org.uk</a>
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Terms of Service Section -->
                    <div id="terms" class="faq-section mb-5">
                        <h2 class="section-title mb-4">
                            <i class="bi bi-file-text me-2" style="color: var(--secondary-color);"></i>
                            Terms & Conditions
                        </h2>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 mb-3">Who We Are</h3>
                                <p class="mb-3">ECONOMICS MADE SIMPLE LTD (“EMS”, “we”, “our”, “us”) is a UK-registered
                                    limited company providing educational resources, subscriptions, and online tuition
                                    worldwide. Contact: <a
                                        href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a>.</p>

                                <h3 class="h5 mb-3">Acceptance of Terms</h3>
                                <p class="mb-3">By accessing and using our website, purchasing our products, or using our
                                    services, you accept and agree to be bound by these Terms & Conditions.</p>

                                <h3 class="h5 mb-3">Eligibility & Accounts</h3>
                                <ul class="mb-3">
                                    <li>Provide accurate information and keep your password secure.</li>
                                    <li>Accounts are for one person; password sharing is prohibited.</li>
                                    <li>Users under 18 must have consent from a parent, guardian, or school.</li>
                                </ul>

                                <h3 class="h5 mb-3">Products & Services</h3>
                                <ul class="mb-3">
                                    <li>Digital resources (PowerPoints, model essays, revision materials).</li>
                                    <li>Subscription services with restricted online content.</li>
                                    <li>Online tuition services.</li>
                                    <li>Other digital learning tools added in the future.</li>
                                </ul>

                                <h3 class="h5 mb-3">Pricing, Taxes & Payment</h3>
                                <p class="mb-3">Prices are in GBP unless stated. Taxes may apply. Payments are processed
                                    by Stripe or PayPal. We do not store full card details. EMS and its processors are
                                    authorised to take payment for purchases and renewals. Pricing or payment errors may be
                                    corrected, with refunds if necessary.</p>

                                <h3 class="h5 mb-3">Subscriptions & Auto-Renew</h3>
                                <p class="mb-3">Subscriptions default to auto-renew. Cancelling stops future billing but
                                    does not refund the current term. Access continues until the end of the paid period.</p>

                                <h3 class="h5 mb-3">Refunds & Cancellations</h3>
                                <ul class="mb-3">
                                    <li>Digital Content: No refunds once accessed/downloaded.</li>
                                    <li>Subscriptions: No refunds for current term; future renewals can be stopped anytime.
                                    </li>
                                    <li>Tuition: Non-refundable once booked unless EMS cancels; then refund or reschedule is
                                        offered.</li>
                                </ul>

                                <h3 class="h5 mb-3">Licence & Intellectual Property</h3>
                                <p class="mb-3">All EMS content is owned by EMS or its licensors. Personal licence is
                                    non-exclusive and non-transferable for study or teaching preparation. Institutions must
                                    purchase a separate licence for sharing content.</p>

                                <h3 class="h5 mb-3">Acceptable Use</h3>
                                <ul class="mb-3">
                                    <li>Do not share logins or allow unauthorised access.</li>
                                    <li>Do not copy, scrape, or bulk-download materials.</li>
                                    <li>Do not remove watermarks or rights notices.</li>
                                    <li>Do not use EMS to break laws or infringe rights.</li>
                                </ul>

                                <h3 class="h5 mb-3">Termination & Suspension</h3>
                                <p class="mb-3">EMS may suspend or terminate your account for breaches of these Terms. No
                                    refund is due if terminated for breach. Access may be disabled to protect intellectual
                                    property.</p>

                                <h3 class="h5 mb-3">Availability & Force Majeure</h3>
                                <p class="mb-3">EMS aims for service availability but does not guarantee uninterrupted
                                    access. We are not liable for failures caused by events beyond our control, including
                                    outages, strikes, natural disasters, or legal changes.</p>

                                <h3 class="h5 mb-3">Accuracy of Information</h3>
                                <p class="mb-3">Materials may contain errors. EMS may update content without notice. This
                                    does not remove mandatory legal rights.</p>

                                <h3 class="h5 mb-3">Third-Party Services & Links</h3>
                                <p class="mb-3">Our site may link to or use third-party services, which are subject to
                                    their own terms. EMS is not responsible for third-party acts or omissions.</p>

                                <h3 class="h5 mb-3">Limitation of Liability</h3>
                                <p class="mb-3">EMS’s total liability is limited to the amount paid for the
                                    product/service. EMS is not liable for exam results, downtime, data loss,
                                    business/profit loss, or indirect/consequential losses, except where law cannot exclude
                                    liability (death, personal injury, or fraud).</p>

                                <h3 class="h5 mb-3">Indemnity</h3>
                                <p class="mb-3">You agree to indemnify EMS against claims or losses arising from unlawful
                                    use, breach of these Terms, or infringement of intellectual property or privacy rights.
                                </p>

                                <h3 class="h5 mb-3">Privacy & Cookies</h3>
                                <p class="mb-3">Your use of EMS is also governed by our <a href="#privacy">Privacy &
                                        Cookies Policy</a>, which explains how we collect and use data.</p>

                                <h3 class="h5 mb-3">Changes to Services & Terms</h3>
                                <p class="mb-3">EMS may change or discontinue services and update these Terms. Continued
                                    use after changes constitutes acceptance of the updated Terms.</p>

                                <h3 class="h5 mb-3">Governing Law & Jurisdiction</h3>
                                <p class="mb-3">These Terms are governed by the laws of England and Wales. Disputes are
                                    resolved exclusively in the courts of England and Wales.</p>

                                <h3 class="h5 mb-3">Contact</h3>
                                <p class="mb-0">Email: <a
                                        href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a></p>
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
                    <p class="section-subtitle mb-4">Our customer service team is here to help you find the answers you
                        need.</p>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="support-option">
                                <i class="bi bi-envelope-fill fs-1 mb-3" style="color: var(--primary-color);"></i>
                                <h4 class="h5 mb-2">Email Us</h4>
                                <p class="text-muted small mb-2">Get a response within 24 hours</p>
                                <a href="mailto:support@eternareads.com" class="btn custom-btn-outline btn-sm">Send
                                    Email</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="support-option">
                                <i class="bi bi-telephone-fill fs-1 mb-3" style="color: var(--secondary-color);"></i>
                                <h4 class="h5 mb-2">Call Us</h4>
                                <p class="text-muted small mb-2">Speak with our team directly</p>
                                <a href="tel:+15551234567" class="btn custom-btn-outline btn-sm">Call Now</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="support-option">
                                <i class="bi bi-chat-fill fs-1 mb-3" style="color: var(--success-color);"></i>
                                <h4 class="h5 mb-2">Live Chat</h4>
                                <p class="text-muted small mb-2">Chat with us in real-time</p>
                                <a href="{{ route('contact') }}" class="btn custom-btn-outline btn-sm">Start Chat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
