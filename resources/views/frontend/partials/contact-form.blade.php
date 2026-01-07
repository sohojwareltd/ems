<section class="contact-form py-5" id="contact-form" style="background: var(--light-bg);">
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
                                        id="first_name" name="first_name"
                                        value="{{ old('first_name', auth()->user()->name ?? '') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name"
                                        value="{{ old('last_name', auth()->user()->lastname ?? '') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                  <div class="col-6">
                                    <label for="contact_category_id" class="form-label">Category *</label>
                                    <select class="form-select @error('contact_category_id') is-invalid @enderror" id="contact_category_id"
                                        name="contact_category_id" required>
                                        <option value="">Select a category</option>
                                        @foreach($contactCategories ?? [] as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('contact_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contact_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="phone" class="form-label">WhatsApp Number</label>
                                    <div class="input-group">
                                        <select class="form-select @error('country_code') is-invalid @enderror" 
                                            id="country_code" name="country_code" style="max-width: 190px;">
                                            <option value="">Code</option>
                                            @php
                                                $countries = \App\Models\Country::query()->orderByRaw('LOWER(name) ASC')->get();
                                            @endphp
                                            @foreach($countries as $country)
                                                <option value="{{ $country->calling_code }}" 
                                                    {{ old('country_code', '+880') == $country->calling_code ? 'selected' : '' }}>
                                                    {{ $country->name }} ({{ $country->calling_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" placeholder="Enter WhatsApp Number"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('country_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                                            Subscribe to our newsletter
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
