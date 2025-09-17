<section class="contact-form py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="section-title mb-3">Send Us a Message</h2>
                    <p class="section-subtitle">Have a question, suggestion, or just want to say hello? We'd love to
                        hear from you!</p>
                </div>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <form action="{{ route('contact.store') }}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <select class="form-select @error('subject') is-invalid @enderror" id="subject"
                                        name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry"
                                            {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry
                                        </option>
                                        <option value="Book Recommendation"
                                            {{ old('subject') == 'Book Recommendation' ? 'selected' : '' }}>Book
                                            Recommendation</option>
                                        <option value="Order Support"
                                            {{ old('subject') == 'Order Support' ? 'selected' : '' }}>Order Support
                                        </option>
                                        <option value="Gift Box Inquiry"
                                            {{ old('subject') == 'Gift Box Inquiry' ? 'selected' : '' }}>Gift Box
                                            Inquiry</option>
                                        <option value="Audiobook Support"
                                            {{ old('subject') == 'Audiobook Support' ? 'selected' : '' }}>Audiobook
                                            Support</option>
                                        <option value="Partnership"
                                            {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership
                                        </option>
                                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6"
                                        placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input @error('newsletter') is-invalid @enderror"
                                            type="checkbox" id="newsletter" name="newsletter"
                                            {{ old('newsletter') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter">
                                            Subscribe to our newsletter for book recommendations and updates
                                        </label>
                                        @error('newsletter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn custom-btn btn-lg px-5">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 