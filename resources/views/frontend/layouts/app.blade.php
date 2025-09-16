@php
    use Datlechin\FilamentMenuBuilder\Models\Menu;
    $menu = Menu::location('main');
    $mobileMenu = Menu::location('mobile');
    $quickLinks = Menu::location('quick_links');
    $customerService = Menu::location('customer_service');
@endphp

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', setting('seo.meta_title', config('app.name')))</title>
    <link rel="icon" href="{{ Storage::url(setting('store.favicon', 'favicon.ico')) }}" type="image/x-icon">
    <meta name="description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('seo.meta_keywords'))">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('styles')
    @vite('resources/js/app.js')
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3"
        style="border-bottom: 1px solid var(--border-color);z-index: 1030;">
        <div class="container-fluid px-lg-5">
            <!-- Logo -->
            @if (setting('store.logo'))
                <a href="{{ route('home') }}">
                    <img src="{{ Storage::url(setting('store.logo')) }}" style="width:155px; height: auto;"
                        alt="Logo" class="img-fluid">
                </a>
            @else
                <a class="navbar-brand d-flex align-items-center me-lg-5" href="{{ route('home') }}"
                    style="font-size: 2rem;">
                    <i class="bi bi-book" style="font-size: 2.2rem; color: var(--secondary-color);"></i>
                    <span class="ms-2 d-none d-sm-inline"
                        style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 2rem; color: var(--primary-color); letter-spacing: 1px;">Eterna
                        Reads</span>
                </a>
            @endif
            <!-- Cart & Hamburger (mobile) -->
            <div class="d-lg-none d-flex align-items-center ms-auto gap-2">
                <a class="nav-link position-relative px-2" href="{{ route('cart.index') }}" title="Cart">
                    <i class="bi bi-cart3 fs-4"></i>
                    <span class="cart-badge" id="cart-count-mobile">{{ \App\Facades\Cart::getItemCount() }}</span>
                </a>
                <button class="navbar-toggler ms-1" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileOffcanvas" aria-controls="mobileOffcanvas" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <!-- Desktop Nav -->
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3 gap-2">
                    @foreach ($menu->menuItems as $item)
                        <li class="nav-item">
                            <a class="nav-link px-3 @if (url()->current() == asset($item->url)) active @endif"
                                href="{{ asset($item->url) }}">
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach

                </ul>
                <!-- User/Cart Actions -->
                <ul class="navbar-nav ms-lg-4 mb-2 mb-lg-0 align-items-lg-center flex-row gap-lg-2 gap-1">
                    <li class="nav-item">
                        <a class="nav-link position-relative px-3" href="{{ route('cart.index') }}" title="Cart">
                            <i class="bi bi-cart3 fs-4"></i>
                            <span class="cart-badge" id="cart-count">{{ \App\Facades\Cart::getItemCount() }}</span>
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i
                                            class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>

                                <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i
                                            class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders.index') }}"><i
                                            class="bi bi-bag me-2"></i>Orders</a></li>
                                <li>
                                <li><a class="dropdown-item" href="{{route('user.orders.download')}}"><i class="fas fa-download"></i>  Download</a></li>
                                <li><a class="dropdown-item" href="{{route('user.subscription')}}"><i class="fa-regular fa-file"></i>  Subscriptions</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <!-- Offcanvas Mobile Nav (moved outside .container-fluid) -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileOffcanvas"
        aria-labelledby="mobileOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileOffcanvasLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav mb-3">
                @foreach ($mobileMenu->menuItems as $item)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset($item->url) }}">{{ $item->title }}</a>
                    </li>
                @endforeach

            </ul>
            <hr>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>
                            Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i>
                            Register</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}"><i
                                class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.profile') }}"><i
                                class="bi bi-person me-2"></i>Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.orders.index') }}"><i class="bi bi-bag me-2"></i>My
                            Orders</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 py-5" style="background: var(--light-bg); min-height: 80vh;">
        <div class="container-lg px-lg-5">
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
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        @if (setting('store.footer_logo'))
                            <img src="{{ Storage::url(setting('store.footer_logo')) }}"
                                style="width: 150px; height: auto;" alt="Logo" class="img-fluid">
                        @else
                            <h4><i class="bi bi-book me-2"></i>{{ setting('store.name') }}</h4>
                        @endif
                        <p class="mb-3">Your literary haven for physical books, and curated gift boxes.
                            Discover the magic of reading with us.</p>
                        <div class="social-links">
                            <a href="{{ setting('store.facebook') }}" class="social-link me-2" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="{{ setting('store.instagram') }}" class="social-link me-2" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="{{ setting('store.twitter') }}" class="social-link me-2" title="Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="{{ setting('store.youtube') }}" class="social-link" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        @foreach ($quickLinks->menuItems as $item)
                            <li><a href="{{ asset($item->url) }}">{{ $item->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled footer-links">
                        @foreach ($customerService->menuItems as $item)
                            <li><a href="{{ asset($item->url) }}">{{ $item->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5>Contact Info</h5>
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt me-2 text-warning"></i>
                            <span>{{ setting('store.address') }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone me-2 text-success"></i>
                            <span>{{ setting('store.phone') }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope me-2 text-info"></i>
                            <span>{{ setting('store.email') }}</< /span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="newsletter-section py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0"><i class="bi bi-envelope-open me-2"></i>Subscribe to our newsletter for
                            book recommendations and updates!</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <input type="email" class="form-control me-2" placeholder="Enter your email">
                            <button class="btn btn-primary">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Eterna Reads. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-bottom-links">
                        <a href="{{ route('faq') }}#terms" class="me-3">Terms of Service</a>
                        <a href="{{ route('faq') }}#privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('store.phone')) }}" target="_blank"
            class="whatsapp-float" aria-label="Chat on WhatsApp" title="Chat with us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>
    <!-- Custom Scripts -->
    <script>
        // Cart count update
        function updateCartCount() {
            fetch('{{ route('cart.index') }}')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cartCount = doc.querySelector('#cart-count');
                    const cartCountMobile = doc.querySelector('#cart-count-mobile');
                    if (cartCount) {
                        document.getElementById('cart-count').textContent = cartCount.textContent;
                    }
                    if (cartCountMobile) {
                        document.getElementById('cart-count-mobile').textContent = cartCountMobile.textContent;
                    }
                });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    <script>
        function showToast(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className =
                `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alert.style.zIndex = '9999';
            alert.innerHTML = `
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(alert);
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>

</html>
