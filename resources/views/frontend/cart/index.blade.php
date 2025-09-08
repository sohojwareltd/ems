@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - MyShop')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="section-header text-center mb-5">
        <h1 class="section-title display-4 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--primary-color); letter-spacing: 1px;">
            <i class="bi bi-cart3 me-3"></i>Shopping Cart
        </h1>
        <p class="section-subtitle lead" style="font-family: 'Inter', sans-serif; color: var(--text-muted);">Review your items and proceed to checkout</p>
    </div>

    @if(!empty($cartItems) && count($cartItems) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-lg border-0 premium-cart-card p-4">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                            <i class="bi bi-bag me-2"></i>Cart Items ({{ count($cartItems) }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle mb-0 cart-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:180px;">Product</th>
                                        <th class="text-center" style="min-width:120px;">Quantity</th>
                                        <th class="text-end" style="min-width:90px;">Price</th>
                                        <th class="text-end" style="min-width:90px;">Total</th>
                                        <th class="text-end" style="min-width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $itemKey => $item)
                                    <tr class="cart-item-table-row" data-item-id="{{ $itemKey }}">
                                        <!-- Product -->
                                        <td class="d-flex align-items-center gap-3 py-3">
                                            <img src="{{ $item['image_url'] ?? ($item['product']['image_url'] ?? 'https://via.placeholder.com/64x64?text=No+Image') }}"
                                                 class="img-fluid rounded"
                                                 alt="{{ $item['product_name'] }}"
                                                 style="width: 56px; height: 56px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold" style="font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--primary-color);">{{ $item['product_name'] }}</div>
                                                @if(isset($item['variant']) && $item['variant'])
                                                <small class="text-muted">Variant: {{ implode(', ', array_values($item['variant']['attributes'] ?? [])) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- Quantity -->
                                        <td class="text-center">
                                            @php
                                                $isDigital = true;
                                                $hasVariants = $item['product']['has_variants'] ?? false;
                                                $variantStock = $item['variant']['stock'] ?? null;
                                                $maxStock = $hasVariants && $variantStock ? $variantStock : ($item['product']['stock'] ?? 999);
                                            @endphp
                                            @if($isDigital)
                                                <span class="badge bg-info text-dark">Digital Product (Qty: 1)</span>
                                            @else
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <button class="btn btn-outline-secondary btn-sm quantity-btn px-2" data-action="decrease" data-item-id="{{ $itemKey }}"><i class="bi bi-dash"></i></button>
                                                <input type="number" class="form-control form-control-sm text-center mx-1 quantity-input" value="{{ $item['quantity'] }}" min="1" max="{{ $maxStock }}" data-item-id="{{ $itemKey }}" style="width: 48px;">
                                                <button class="btn btn-outline-secondary btn-sm quantity-btn px-2" data-action="increase" data-item-id="{{ $itemKey }}"><i class="bi bi-plus"></i></button>
                                            </div>
                                            @endif
                                        </td>
                                        <!-- Price -->
                                        <td class="text-end">
                                            <div class="fw-semibold" style="font-size: 1rem;">${{ number_format($item['price'], 2) }}</div>
                                        </td>
                                        <!-- Total -->
                                        <td class="text-end">
                                            <div class="fw-bold" style="font-size: 1.1rem; color: var(--primary-color);">${{ number_format($item['total'], 2) }}</div>
                                        </td>
                                        <!-- Remove -->
                                        <td class="text-end">
                                            <button class="btn btn-link text-danger p-0 remove-item" data-item-id="{{ $itemKey }}" title="Remove item">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Card List -->
                        <div class="d-block d-md-none">
                            @foreach($cartItems as $itemKey => $item)
                            <div class="cart-mobile-card mb-3 p-3 shadow-sm rounded-4 position-relative" data-item-id="{{ $itemKey }}">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <img src="{{ $item['image_url'] ?? ($item['product']['image_url'] ?? 'https://via.placeholder.com/64x64?text=No+Image') }}"
                                         class="img-fluid rounded cart-mobile-img"
                                         alt="{{ $item['product_name'] }}">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-1" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">{{ $item['product_name'] }}</div>
                                        @if(isset($item['variant']) && $item['variant'])
                                        <small class="text-muted">Variant: {{ implode(', ', array_values($item['variant']['attributes'] ?? [])) }}</small>
                                        @endif
                                    </div>
                                    <button class="btn btn-link text-danger p-0 remove-item position-absolute top-0 end-0 mt-2 me-2" data-item-id="{{ $itemKey }}" title="Remove item">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    @php
                                        $isDigital = $item['product']['is_digital'] ?? false;
                                        $hasVariants = $item['product']['has_variants'] ?? false;
                                        $variantStock = $item['variant']['stock'] ?? null;
                                        $maxStock = $hasVariants && $variantStock ? $variantStock : ($item['product']['stock'] ?? 999);
                                    @endphp
                                    @if($isDigital)
                                        <span class="badge bg-info text-dark">Digital Product (Qty: 1)</span>
                                    @else
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-outline-secondary btn-sm quantity-btn px-2" data-action="decrease" data-item-id="{{ $itemKey }}"><i class="bi bi-dash"></i></button>
                                        <input type="number" class="form-control form-control-sm text-center mx-1 quantity-input" value="{{ $item['quantity'] }}" min="1" max="{{ $maxStock }}" data-item-id="{{ $itemKey }}" style="width: 48px;">
                                        <button class="btn btn-outline-secondary btn-sm quantity-btn px-2" data-action="increase" data-item-id="{{ $itemKey }}"><i class="bi bi-plus"></i></button>
                                    </div>
                                    @endif
                                    <div class="text-end">
                                        <div class="fw-semibold small">Price: ${{ number_format($item['price'], 2) }}</div>
                                        <div class="fw-bold small" style="color: var(--primary-color);">Total: ${{ number_format($item['total'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 sticky-top premium-summary-card" style="top: 2rem; background: linear-gradient(135deg, #fff 80%, var(--light-bg) 100%); border-radius: 1.5rem;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--success-color);">
                            <i class="bi bi-calculator me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Coupon Section -->
                        <div class="mb-4 premium-coupon-box p-3 rounded-4" style="background: var(--light-bg);">
                            <h6 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                                <i class="bi bi-ticket-perforated me-2"></i>Apply Coupon
                            </h6>
                            <div class="input-group mb-2">
                                <input type="text"
                                       class="form-control"
                                       id="couponCode"
                                       placeholder="Enter coupon code">
                                <button class="btn btn-premium" id="applyCoupon">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </div>
                            <div id="couponMessage"></div>
                        </div>

                        <!-- Summary Details -->
                        <div class="summary-details premium-summary-details">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span class="fw-bold">-${{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax:</span>
                                <span class="fw-bold">${{ number_format($tax, 2) }}</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5" style="font-family: 'Playfair Display', serif;">Total:</span>
                                <span class="fw-bold fs-4 price premium-price" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}"
                               class="btn btn-premium btn-lg fw-bold">
                                <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <div class="card shadow-lg border-0" style="max-width: 500px; margin: 0 auto;">
                <div class="card-body p-5">
                    <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                    <h3 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-premium btn-lg">
                        <i class="bi bi-shop me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.premium-cart-card {
    border-radius: 1.5rem;
    background: linear-gradient(135deg, #fff 80%, var(--light-bg) 100%);
    padding: 2rem !important;
}
.premium-cart-item {
    border-radius: 1.25rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 12px rgba(155,139,122,0.07);
    background: #fff;
    transition: box-shadow 0.2s, background 0.2s;
}
.premium-cart-item:hover {
    box-shadow: 0 8px 32px rgba(155,139,122,0.13);
    background: var(--light-bg);
}
.premium-cart-img {
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(155,139,122,0.08);
    }
.premium-summary-card {
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px rgba(155,139,122,0.13);
    border: 1.5px solid var(--border-color);
}
.premium-summary-details {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(155,139,122,0.07);
}
.premium-coupon-box {
    border: 1.5px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(155,139,122,0.07);
    }
.premium-remove-btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    transition: background 0.2s, transform 0.2s;
    }
.premium-remove-btn:hover {
    background: rgba(239, 68, 68, 0.08);
        transform: scale(1.1);
    }
.premium-qty-group .btn-premium {
    padding: 0.35rem 0.9rem;
    font-size: 1.1rem;
    border-radius: 1rem;
}
.premium-cart-item-elegant {
    background: #fff;
    border-radius: 1.25rem;
    box-shadow: 0 2px 12px rgba(155,139,122,0.07);
    transition: box-shadow 0.2s, background 0.2s;
    min-height: 120px;
    margin-bottom: 2rem !important;
    border: 1.5px solid var(--border-color);
    }
.premium-cart-item-elegant:hover {
    box-shadow: 0 8px 32px rgba(155,139,122,0.13);
    background: var(--light-bg);
}
.premium-cart-img-elegant {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(155,139,122,0.08);
    border: 2px solid var(--border-color);
    background: #f8f7f5;
}
.premium-qty-group-elegant {
    min-width: 120px;
    border-radius: 2rem;
    border: 1.5px solid var(--border-color);
    background: #fff;
    box-shadow: 0 2px 8px rgba(155,139,122,0.07);
}
.premium-qty-group-elegant .btn-premium {
    font-size: 1.1rem;
    border-radius: 50%;
    padding: 0.25rem 0.7rem;
    min-width: 32px;
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-item-simple {
    font-size: 1rem;
    background: none;
    border-radius: 0;
    box-shadow: none;
    margin-bottom: 0;
    min-height: 72px;
}
.cart-item-simple:last-child {
    border-bottom: none;
    }
.cart-table th, .cart-table td {
    padding: 1.25rem 1rem !important;
    vertical-align: middle;
}
.cart-table td img {
    box-shadow: 0 2px 8px rgba(155,139,122,0.08);
    border-radius: 0.75rem;
}
.cart-mobile-card {
    background: #fff;
    border: 1.5px solid var(--border-color, #eee);
    box-shadow: 0 2px 8px rgba(155,139,122,0.07);
    border-radius: 1.25rem;
    position: relative;
}
.cart-mobile-img {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(155,139,122,0.08);
}
@media (max-width: 767px) {
    .cart-table, .cart-table thead, .cart-table tbody, .cart-table tr, .cart-table td {
        display: none !important;
    }
    .cart-mobile-card {
        display: block;
    }
}
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Quantity buttons
        $('.quantity-btn').on('click', function() {
            const action = $(this).data('action');
            const itemId = $(this).data('item-id');
            const input = $(`.quantity-input[data-item-id="${itemId}"]`);
            let currentQty = parseInt(input.val());

            if (action === 'increase') {
                input.val(currentQty + 1);
            } else if (action === 'decrease' && currentQty > 1) {
                input.val(currentQty - 1);
            }

            updateQuantity(itemId, input.val());
        });

        // Quantity input change
        $('.quantity-input').on('change', function() {
            const itemId = $(this).data('item-id');
            const quantity = $(this).val();
            updateQuantity(itemId, quantity);
        });

        // Remove item
        $('.remove-item').on('click', function() {
            const itemId = $(this).data('item-id');
            removeItem(itemId);
        });

        // Apply coupon
        $('#applyCoupon').on('click', function() {
            const couponCode = $('#couponCode').val().trim();
            if (couponCode) {
                applyCoupon(couponCode);
            }
        });

        // Enter key for coupon
        $('#couponCode').on('keypress', function(e) {
            if (e.which === 13) {
                $('#applyCoupon').click();
            }
        });

        function updateQuantity(itemId, quantity) {
            $.ajax({
                url: '{{ route("cart.update") }}',
                method: 'POST',
                data: {
                    item_id: itemId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        updateCartCount(response.cart_count);
                        updateSummary(response);

                        // Update the specific item's line total
                        if (response.cart_items && response.cart_items[itemId]) {
                            const item = response.cart_items[itemId];
                            const itemElement = $(`.cart-item-table-row[data-item-id="${itemId}"]`);

                            // Update the line total
                            itemElement.find('.fw-bold').text('$' + parseFloat(item.total).toFixed(2));

                            // Update the quantity input to ensure it matches
                            itemElement.find('.quantity-input').val(item.quantity);
                        }

                        showToast('Cart updated successfully', 'success');
                    } else {
                        showToast(response.message || 'Error updating cart', 'danger');
                    }
                },
                error: function() {
                    showToast('Error updating cart', 'danger');
                }
            });
        }

        function removeItem(itemId) {
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: '{{ route("cart.remove") }}',
                    method: 'POST',
                    data: {
                        item_id: itemId
                    },
                    success: function(response) {
                        if (response.success) {
                            updateCartCount(response.cart_count);
                            $(`.cart-item-table-row[data-item-id="${itemId}"]`).fadeOut(300, function() {
                                $(this).remove();
                                if ($('.cart-item-table-row').length === 0) {
                                    location.reload();
                                }
                            });
                            updateSummary(response);
                            showToast('Item removed from cart', 'success');
                        } else {
                            showToast(response.message || 'Error removing item', 'danger');
                        }
                    },
                    error: function() {
                        showToast('Error removing item', 'danger');
                    }
                });
            }
        }

        function applyCoupon(couponCode) {
            const button = $('#applyCoupon');
            const originalText = button.html();

            button.prop('disabled', true);
            button.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '{{ route("cart.apply-coupon") }}',
                method: 'POST',
                data: {
                    coupon_code: couponCode
                },
                success: function(response) {
                    if (response.success) {
                        updateSummary(response);
                        $('#couponMessage').html(`
                            <div class="alert alert-success alert-sm">
                                <i class="bi bi-check-circle me-1"></i>${response.message}
                            </div>
                        `);
                        showToast(response.message, 'success');
                    } else {
                        $('#couponMessage').html(`
                            <div class="alert alert-danger alert-sm">
                                <i class="bi bi-exclamation-triangle me-1"></i>${response.message}
                            </div>
                        `);
                        showToast(response.message, 'danger');
                    }
                },
                error: function() {
                    $('#couponMessage').html(`
                        <div class="alert alert-danger alert-sm">
                            <i class="bi bi-exclamation-triangle me-1"></i>Error applying coupon
                        </div>
                    `);
                    showToast('Error applying coupon', 'danger');
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.html(originalText);
                }
            });
        }

        function updateSummary(data) {
            $('.summary-details').html(`
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-bold">$${parseFloat(data.subtotal || data.cart_total || 0).toFixed(2)}</span>
                </div>
                ${(data.discount || 0) > 0 ? `
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Discount:</span>
                    <span class="fw-bold">-$${parseFloat(data.discount || 0).toFixed(2)}</span>
                </div>
                ` : ''}
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping:</span>
                    <span class="fw-bold">$${parseFloat(data.shipping || 0).toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax:</span>
                    <span class="fw-bold">$${parseFloat(data.tax || 0).toFixed(2)}</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5" style="font-family: 'Playfair Display', serif;">Total:</span>
                    <span class="fw-bold fs-4 price premium-price" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">$${parseFloat(data.total || data.cart_total || 0).toFixed(2)}</span>
                </div>
            `);
        }
    });
</script>
@endpush
@endsection
