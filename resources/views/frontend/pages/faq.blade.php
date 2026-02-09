@extends('frontend.layouts.app')

@section('title', 'FAQ - EMS')
<style>
    #privacy {
        scroll-margin-top: 120px;
        /* Adjust the value as needed */
    }
    #terms {
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
                                <p class="text-muted mb-4"><strong>Last updated:</strong> {{ date('F j, Y') }}</p>
                                
                                <p class="mb-4">These Terms & Conditions (the "Terms") form the contract between Economics Made Simple Ltd ("EMS", "we", "us") and any person or organisation ("you") who accesses our website, buys our products, or uses our services. By using EMS you agree to these Terms.</p>

                                <h3 class="h5 mb-3">1. Who We Are</h3>
                                <p class="mb-3">EMS is a UK‑registered limited company providing educational resources, subscriptions and online tuition worldwide.<br>
                                <strong>Registered name:</strong> ECONOMICS MADE SIMPLE LTD<br>
                                <strong>Contact:</strong> <a href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a></p>

                                <h3 class="h5 mb-3">2. Definitions</h3>
                                <ul class="mb-3">
                                    <li><strong>"Digital Content"</strong> means our downloadable files (e.g., PowerPoints, PDFs), and view‑only materials in a login portal.</li>
                                    <li><strong>"Subscription"</strong> means time‑limited access to online content or services that auto‑renews unless cancelled.</li>
                                    <li><strong>"Institution"</strong> means a school, college, tuition company or other organisation purchasing for multiple users.</li>
                                </ul>

                                <h3 class="h5 mb-3">3. Scope of these Terms & Acceptance</h3>
                                <p class="mb-3">These Terms apply to all use of our website, purchases of Digital Content, Subscriptions and tuition. If you do not agree, do not use EMS. If you create an account or buy from us, you accept these Terms.</p>

                                <h3 class="h5 mb-3">4. Eligibility & Accounts</h3>
                                <ul class="mb-3">
                                    <li>You must provide accurate information and keep your password secure.</li>
                                    <li>Accounts are for one person only. Password sharing is not allowed.</li>
                                    <li>If you are under 18, you confirm you have consent from a parent/guardian or your school.</li>
                                </ul>

                                <h3 class="h5 mb-3">5. Newsletter Subscriptions</h3>
                                <p class="mb-3">When you create an account on Economics Made Simple, you agree to be automatically added to our newsletter mailing list. This allows us to keep you informed about updates, new resources, and relevant educational content. You may opt out of these communications at any time by selecting the unsubscribe link included in each email or by contacting us directly. Unsubscribing from the newsletter does not affect your ability to maintain an account or access purchased resources.</p>

                                <h3 class="h5 mb-3">6. Products & Services</h3>
                                <ul class="mb-3">
                                    <li>Digital resources (PowerPoints, model essays, revision materials).</li>
                                    <li>Subscription services with access to restricted online content.</li>
                                    <li>Online tuition services.</li>
                                    <li>Other digital learning tools that we may add.</li>
                                </ul>
                                <p class="mb-3">Some resources are instant digital downloads. Others are view‑only inside a secure login portal.</p>

                                <h3 class="h5 mb-3">7. Pricing, Taxes & Payment</h3>
                                <ul class="mb-3">
                                    <li>Prices are shown in GBP unless stated. Taxes (including VAT or equivalent) may apply based on your location.</li>
                                    <li>Payments are processed by Stripe or PayPal, or by card via those processors. We do not store full card details.</li>
                                    <li>You authorise EMS and our payment processors to take payment for your purchases and renewals.</li>
                                </ul>
                                <p class="mb-3"><strong>Pricing/Payment Errors:</strong> If a price or payment error occurs (for example a clear mispricing or duplicate charge), we may cancel the order, correct the error, and refund any amounts wrongly taken.</p>

                                <h3 class="h5 mb-3">8. Subscriptions & Auto‑Renew</h3>
                                <ul class="mb-3">
                                    <li>Plans: monthly, 6‑month, and annual. Default is auto‑renew.</li>
                                    <li>Cancelling auto‑renew stops future billing. It does not refund the current paid term.</li>
                                    <li>Access continues until the end of the current term. There is no pro‑rata refund.</li>
                                </ul>

                                <h3 class="h5 mb-3">9. Refunds, Cancellations & Digital Content</h3>
                                <p class="mb-3"><strong>Digital Content:</strong> By choosing immediate access/download you agree your statutory cooling‑off right ends when access starts. Therefore, we do not offer refunds once Digital Content has been accessed or downloaded.</p>
                                <p class="mb-3"><strong>Subscriptions:</strong> No refunds are given for the current paid term. You can stop future renewals at any time from your account.</p>
                                <p class="mb-3"><strong>Tuition:</strong> Sessions are non‑refundable once booked unless we cancel. If we cancel, we will offer a new time or a refund.</p>

                                <h3 class="h5 mb-3">10. Licence & Intellectual Property</h3>
                                <p class="mb-3"><strong>Ownership:</strong> All EMS content (including text, diagrams, videos, PowerPoints, and branding) is owned by EMS or our licensors.</p>
                                <p class="mb-3"><strong>Personal Licence (default):</strong> When you buy, you receive a personal, non‑exclusive, non‑transferable licence to use the content for your own study or teaching preparation. You must not copy, upload, share, resell, or distribute our content.</p>
                                <p class="mb-3"><strong>Institutional Use:</strong> Schools and other organisations must purchase an institutional licence from EMS before sharing our content with staff or students. One personal purchase does not authorise departmental or whole‑school use.</p>

                                <h3 class="h5 mb-3">11. Acceptable Use</h3>
                                <ul class="mb-3">
                                    <li>Do not share your login or enable unauthorised access.</li>
                                    <li>Do not copy, scrape, or bulk‑download our materials.</li>
                                    <li>Do not remove watermarks or rights‑management notices.</li>
                                    <li>Do not use EMS to break any law or to infringe anyone's rights.</li>
                                </ul>

                                <h3 class="h5 mb-3">12. Termination & Suspension</h3>
                                <p class="mb-3">We may suspend or terminate your account immediately (with or without notice) if we reasonably believe you breached these Terms (e.g., password sharing, copying or redistribution). If we terminate for breach, no refund is due. We may also disable access to specific content to protect our IP.</p>

                                <h3 class="h5 mb-3">13. Availability, Maintenance & Force Majeure</h3>
                                <p class="mb-3">We aim to keep EMS available, but we do not guarantee uninterrupted service. We may perform maintenance and updates. We are not responsible for failure or delay caused by events beyond our reasonable control (including internet or hosting outages, denial‑of‑service attacks, strikes, fires, floods, war, government actions, or changes in law).</p>

                                <h3 class="h5 mb-3">14. Accuracy of Information (Typos & Updates)</h3>
                                <p class="mb-3">We take care to ensure accuracy, but materials may contain technical, typographical, or content errors. We may update, correct, or improve content at any time without notice. This clause does not remove your legal rights where mandatory.</p>

                                <h3 class="h5 mb-3">15. Third‑Party Services & Links</h3>
                                <p class="mb-3">Our site may link to third‑party sites or use third‑party tools (e.g., payment processors, analytics). Those services are subject to their own terms and privacy policies. We are not responsible for third‑party acts or omissions.</p>

                                <h3 class="h5 mb-3">16. Limitation of Liability</h3>
                                <p class="mb-3">To the fullest extent permitted by law, our total liability for any claim arising out of or relating to your use of EMS is limited to the amount you paid to EMS for the product or service giving rise to the claim.</p>
                                <p class="mb-3">We do not accept liability for: exam results or educational outcomes; service downtime or delays; loss of data; loss of business, profits, or goodwill; or any indirect or consequential losses. Nothing in these Terms excludes liability that cannot be excluded by law (such as for death or personal injury caused by negligence, or fraud).</p>

                                <h3 class="h5 mb-3">17. Indemnity</h3>
                                <p class="mb-3">You agree to indemnify EMS against claims, costs and losses arising from your unlawful use of EMS, your breach of these Terms, or your infringement of intellectual property or privacy rights.</p>

                                <h3 class="h5 mb-3">18. Privacy & Cookies</h3>
                                <p class="mb-3">Your use of EMS is also governed by our <a href="#privacy">Privacy & Cookies Policy</a>, which explains what data we collect and how we use it. By using EMS you agree to that policy.</p>

                                <h3 class="h5 mb-3">19. Changes to Services and to these Terms</h3>
                                <p class="mb-3">We may change or discontinue features or content. We may update these Terms from time to time. Changes take effect when posted on our website. If you keep using EMS after changes, you accept the updated Terms.</p>

                                <h3 class="h5 mb-3">20. Governing Law & Jurisdiction</h3>
                                <p class="mb-3">These Terms are governed by the laws of England and Wales. Disputes will be resolved exclusively in the courts of England and Wales.</p>

                                <h3 class="h5 mb-3">21. Contact ECONOMICS MADE SIMPLE LTD</h3>
                                <p class="mb-3">Email: <a href="mailto:info@economicsmadesimple.com">info@economicsmadesimple.com</a></p>

                                <div class="alert alert-info mt-4">
                                    <h4 class="h6 mb-2"><strong>Important Checkout Notices</strong></h4>
                                    <ul class="mb-0 small">
                                        <li><strong>Digital Content:</strong> "By accessing or downloading this digital product immediately, I understand I will lose my right to cancel and I am not entitled to a refund."</li>
                                        <li><strong>Subscriptions:</strong> "You can cancel auto‑renew at any time. The current paid term is non‑refundable."</li>
                                    </ul>
                                </div>
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
                        <div class="col-md-6">
                            <div class="support-option">
                                <i class="bi bi-envelope-fill fs-1 mb-3" style="color: var(--primary-color);"></i>
                                <h4 class="h5 mb-2">Email Us</h4>
                                {{-- <p class="text-muted small mb-2">Get a response within 24 hours</p> --}}
                                <a href="mailto:support@eternareads.com" class="btn custom-btn-outline btn-sm">Send
                                    Email</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="support-option">
                                <i class="bi bi-telephone-fill fs-1 mb-3" style="color: var(--secondary-color);"></i>
                                <h4 class="h5 mb-2">WhatsApp Us</h4>
                                {{-- <p class="text-muted small mb-2">Speak with our team directly</p> --}}
                                <a href="tel:+15551234567" class="btn custom-btn-outline btn-sm">Send Message</a>
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="support-option">
                                <i class="bi bi-chat-fill fs-1 mb-3" style="color: var(--success-color);"></i>
                                <h4 class="h5 mb-2">Live Chat</h4>
                                <p class="text-muted small mb-2">Chat with us in real-time</p>
                                <a href="{{ route('contact') }}" class="btn custom-btn-outline btn-sm">Start Chat</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
