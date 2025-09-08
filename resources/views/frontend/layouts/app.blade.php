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

    <style>
        :root {
            --primary-color: #9B8B7A;
            --primary-dark: #7A6B5A;
            --secondary-color: #A8B5A0;
            --accent-color: #D4C4B7;
            --success-color: #8BA892;
            --danger-color: #B87A7A;
            --warning-color: #D4B483;
            --info-color: #8BA8B5;
            --light-bg: #FAF9F7;
            --border-color: #E8E0D8;
            --text-muted: #8A7F72;
            --text-dark: #4A3F35;
            --white: #FFFFFF;
            --shadow-soft: 0 2px 8px rgba(155, 139, 122, 0.08);
            --shadow-medium: 0 4px 16px rgba(155, 139, 122, 0.12);
            --shadow-strong: 0 8px 32px rgba(155, 139, 122, 0.16);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 500;
        }

        body {
            background-color: var(--white);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-soft);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.75rem;
            color: var(--primary-color) !important;
        }

        .navbar-brand i {
            color: var(--secondary-color);
            margin-right: 0.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(155, 139, 122, 0.08);
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(155, 139, 122, 0.12);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
            min-width: 18px;
            text-align: center;
            font-weight: 600;
            box-shadow: var(--shadow-soft);
        }

        /* Product Cards */
        .product-card {
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            background: var(--white);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
            border-color: var(--accent-color);
        }

        .product-image {
            height: 280px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.02);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            box-shadow: var(--shadow-soft);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-medium);
        }

        .btn-outline-primary {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-success:hover {
            background: #7A9A82;
            transform: translateY(-1px);
        }

        .btn-add-to-cart {
            transition: all 0.3s ease;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-add-to-cart:hover {
            transform: scale(1.02);
        }

        /* Typography and Text */
        .price {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .original-price {
            text-decoration: line-through;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .badge {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }

        /* Alerts and Forms */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: var(--shadow-soft);
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(155, 139, 122, 0.15);
        }

        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(155, 139, 122, 0.15);
        }

        /* Cards and Containers */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
        }

        .card-header {
            border-bottom: 1px solid var(--border-color);
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            background: var(--light-bg);
        }

        /* Dropdowns */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-medium);
        }

        .dropdown-item {
            border-radius: 6px;
            margin: 2px 6px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(155, 139, 122, 0.08);
            color: var(--primary-color);
        }

        /* Section Headers */
        .section-header {
            background: var(--light-bg);
            padding: 3rem 0;
            margin-bottom: 3rem;
            border-radius: 12px;
        }

        .section-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--light-bg) 0%, rgba(168, 181, 160, 0.1) 100%);
            padding: 4rem 0;
        }

        /* Quick Links */
        .quick-link-card {
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--white);
            box-shadow: var(--shadow-soft);
        }

        .quick-link-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
            border-color: var(--accent-color);
        }

        .quick-link-icon {
            color: var(--primary-color);
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: var(--white);
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }

        .footer h5 {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-links {
            display: flex;
            align-items: center;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--accent-color);
            color: var(--text-dark) !important;
            transform: translateY(-2px);
        }

        .footer-links li a {
            transition: all 0.3s ease;
            padding: 0.25rem 0;
        }

        .footer-links li a:hover {
            color: var(--accent-color) !important;
            transform: translateX(4px);
        }

        .newsletter-section {
            background: rgba(255, 255, 255, 0.05);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .newsletter-section .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .newsletter-section .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-section .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-color);
            color: white;
        }

        .newsletter-section .btn {
            background: var(--accent-color);
            border: none;
            color: var(--text-dark);
        }

        .newsletter-section .btn:hover {
            background: #C4B4A7;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }

            .product-card {
                margin-bottom: 1.5rem;
            }

            .btn {
                padding: 0.625rem 1.25rem;
            }

            .footer-main {
                text-align: center;
            }

            .social-links {
                justify-content: center;
                margin-top: 1rem;
            }

            .newsletter-section .d-flex {
                flex-direction: column;
            }

            .newsletter-section .btn {
                margin-top: 1rem;
            }

            .hero-section {
                padding: 2rem 0;
            }

            .section-header {
                padding: 2rem 0;
                margin-bottom: 2rem;
            }
        }

        /* Loading and Animations */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Custom toast */
        .custom-toast {
            z-index: 9999;
        }

        .custom-toast .alert {
            border-radius: 8px;
            box-shadow: var(--shadow-medium);
        }

        @media (max-width: 991.98px) {
            .navbar {
                padding: 0.5rem 0 !important;
            }

            .navbar-brand {
                font-size: 1.2rem !important;
            }

            .navbar-brand .bi-book {
                font-size: 1.5rem !important;
            }

            .navbar .cart-badge {
                font-size: 0.8rem;
                min-width: 16px;
                top: -4px;
                right: -4px;
            }
        }
    </style>

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
                <img src="{{ Storage::url(setting('store.logo')) }}" style="width:60px; height: auto;" alt="Logo"
                    class="img-fluid">
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
                                            class="bi bi-bag me-2"></i>My Orders</a></li>
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

        <div class="container">
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
