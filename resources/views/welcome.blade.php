<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">
                <i class="bi bi-shop"></i> MyShop
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('products.index') }}">
                    <i class="bi bi-grid"></i> Products
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart3"></i> Cart
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to MyShop</h1>
            <p class="lead mb-4">Discover amazing products at great prices</p>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4">
                <i class="bi bi-shop"></i> Start Shopping
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Why Choose MyShop?</h2>
                    <p class="text-muted">We provide the best shopping experience</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-truck feature-icon mb-3"></i>
                            <h5 class="card-title">Fast Shipping</h5>
                            <p class="card-text text-muted">Get your orders delivered quickly and securely.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-shield-check feature-icon mb-3"></i>
                            <h5 class="card-title">Secure Payments</h5>
                            <p class="card-text text-muted">Your payment information is always protected.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-headset feature-icon mb-3"></i>
                            <h5 class="card-title">24/7 Support</h5>
                            <p class="card-text text-muted">Our customer support team is always here to help.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-3">Ready to Start Shopping?</h3>
            <p class="text-muted mb-4">Browse our collection of amazing products</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid"></i> View Products
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>MyShop</h5>
                    <p class="text-muted">Your trusted online store for quality products.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} MyShop. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
