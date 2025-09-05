@php
    $sliders = \App\Models\Slider::active()->ordered()->get();
@endphp

@if($sliders->count() > 0)
<div id="heroSlider" class="carousel slide mb-5" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        @foreach($sliders as $index => $slider)
        <button type="button" 
                data-bs-target="#heroSlider" 
                data-bs-slide-to="{{ $index }}" 
                class="{{ $index === 0 ? 'active' : '' }}"
                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}">
        </button>
        @endforeach
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <div class="position-relative">
                <img src="{{ $slider->image_url }}" 
                     class="d-block w-100" 
                     style="height: 500px; object-fit: cover;" 
                     alt="{{ $slider->title }}">
                
                <!-- Overlay -->
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
                
                <!-- Content -->
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                    <div class="container">
                        <h2 class="display-4 fw-bold mb-3">{{ $slider->title }}</h2>
                        @if($slider->subtitle)
                        <p class="lead mb-4">{{ $slider->subtitle }}</p>
                        @endif
                        @if($slider->button_text && $slider->button_url)
                        <a href="{{ $slider->button_url }}" 
                           class="btn btn-primary btn-lg px-4 py-2">
                            {{ $slider->button_text }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<style>
.carousel-item {
    transition: transform 0.6s ease-in-out;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 1rem;
}

.carousel-indicators {
    bottom: 2rem;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 4px;
}

@media (max-width: 768px) {
    .carousel-item img {
        height: 300px !important;
    }
    
    .carousel-item .display-4 {
        font-size: 2rem;
    }
    
    .carousel-item .lead {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-play the carousel
    const carousel = new bootstrap.Carousel(document.getElementById('heroSlider'), {
        interval: 5000, // 5 seconds
        wrap: true
    });
});
</script>
@endif 