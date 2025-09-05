# Laravel CartService - Facade Usage Guide

A modern, feature-rich shopping cart system for Laravel using the `Cart` facade. No API needed - use directly in controllers, Livewire, Blade views, and anywhere in your Laravel app.

---

## Features
- **Session cart** for guests, **Database cart** for logged-in users
- **Cart merging** on login (guest cart → user cart)
- **Abandoned cart tracking** with email notifications
- **Coupon/discount support** (percentage or fixed amount)
- **Tax and shipping calculation**
- **Easy facade usage**: `Cart::method()`
- **Frontend agnostic** - works with any UI framework

---

## Installation

1. **Register Service Provider** in `config/app.php`:
```php
'providers' => [
    // ... other providers
    App\Providers\CartServiceProvider::class,
],
```

2. **Register Facade Alias** in `config/app.php`:
```php
'aliases' => [
    // ... other aliases
    'Cart' => App\Facades\Cart::class,
],
```

3. **Run Migrations**:
```bash
php artisan migrate
```

---

## Basic Usage

### Add Product to Cart
```php
// Simple add
Cart::add($productId, 2);

// With options (size, color, etc.)
Cart::add($productId, 1, ['size' => 'L', 'color' => 'Blue']);

// Response: ['success' => true, 'message' => 'Product added to cart', 'cart_count' => 3, 'cart_total' => 120.00]
```

### Update Quantity
```php
Cart::update($productId, 5, ['size' => 'L']);
```

### Remove Item
```php
Cart::remove($productId, ['size' => 'L']);
```

### Clear Entire Cart
```php
Cart::clear();
```

### Get Cart Summary
```php
$summary = Cart::getSummary();
// Returns: ['items' => [...], 'item_count' => 3, 'subtotal' => 100.00, 'discount' => 10.00, 'tax' => 5.00, 'shipping' => 5.99, 'total' => 100.99]
```

---

## Advanced Usage

### Coupons & Discounts
```php
// Apply coupon
Cart::addCoupon('SAVE20');

// Remove coupon
Cart::removeCoupon('SAVE20');
```

### Tax & Shipping
```php
// Set tax rate (10%)
Cart::setTaxRate(10);

// Set shipping cost
Cart::setShippingCost(5.99);
```

### Get Specific Values
```php
$itemCount = Cart::getItemCount();
$subtotal = Cart::getSubtotal();
$total = Cart::getTotal();
$discount = Cart::getDiscountTotal();
$tax = Cart::getTaxAmount();
$shipping = Cart::getShippingCost();
```

---

## Usage in Controllers

### Product Controller
```php
class ProductController extends Controller
{
    public function addToCart(Request $request)
    {
        $result = Cart::add(
            $request->product_id,
            $request->quantity,
            $request->options ?? []
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function cart()
    {
        $cartSummary = Cart::getSummary();
        return view('cart.index', compact('cartSummary'));
    }

    public function updateCart(Request $request)
    {
        $result = Cart::update(
            $request->product_id,
            $request->quantity,
            $request->options ?? []
        );

        return response()->json($result);
    }
}
```

### Checkout Controller
```php
class CheckoutController extends Controller
{
    public function index()
    {
        // Set tax rate based on user location
        Cart::setTaxRate(8.5);
        
        // Set shipping cost
        Cart::setShippingCost(9.99);
        
        $cartSummary = Cart::getSummary();
        
        if ($cartSummary['item_count'] === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        return view('checkout.index', compact('cartSummary'));
    }

    public function applyCoupon(Request $request)
    {
        $result = Cart::addCoupon($request->coupon_code);
        
        if ($result['success']) {
            return redirect()->back()->with('success', 'Coupon applied successfully!');
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
}
```

---

## Usage in Livewire Components

### Cart Component
```php
class CartComponent extends Component
{
    public $cartSummary;

    public function mount()
    {
        $this->cartSummary = Cart::getSummary();
    }

    public function updateQuantity($productId, $quantity, $options = [])
    {
        $result = Cart::update($productId, $quantity, $options);
        
        if ($result['success']) {
            $this->cartSummary = Cart::getSummary();
            $this->dispatch('cart-updated', $result);
        }
    }

    public function removeItem($productId, $options = [])
    {
        $result = Cart::remove($productId, $options);
        
        if ($result['success']) {
            $this->cartSummary = Cart::getSummary();
            $this->dispatch('cart-updated', $result);
        }
    }

    public function applyCoupon($code)
    {
        $result = Cart::addCoupon($code);
        
        if ($result['success']) {
            $this->cartSummary = Cart::getSummary();
            $this->dispatch('coupon-applied');
        }
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
```

---

## Usage in Blade Views

### Cart Icon with Count
```php
<!-- In your layout or navbar -->
<div class="cart-icon">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count">{{ Cart::getItemCount() }}</span>
</div>
```

### Cart Summary in Sidebar
```php
<!-- In sidebar or cart widget -->
@if(Cart::getItemCount() > 0)
    <div class="cart-widget">
        <h4>Cart ({{ Cart::getItemCount() }} items)</h4>
        <div class="cart-items">
            @foreach(Cart::getItems() as $item)
                <div class="cart-item">
                    <span>{{ $item['product_name'] }}</span>
                    <span>Qty: {{ $item['quantity'] }}</span>
                    <span>${{ number_format($item['total'], 2) }}</span>
                </div>
            @endforeach
        </div>
        <div class="cart-total">
            <strong>Total: ${{ number_format(Cart::getTotal(), 2) }}</strong>
        </div>
    </div>
@endif
```

---

## Cart Merging on Login

### In LoginController or Auth Event
```php
// When user logs in, merge guest cart with user cart
public function authenticated(Request $request, $user)
{
    CartService::mergeGuestCart($user);
}
```

### Or in Event Listener
```php
class LoginListener
{
    public function handle($event)
    {
        if ($event->user) {
            CartService::mergeGuestCart($event->user);
        }
    }
}
```

---

## Abandoned Cart Tracking

### Schedule Commands (in `app/Console/Kernel.php`)
```php
protected function schedule(Schedule $schedule)
{
    // Track abandoned carts every hour
    $schedule->call([CartService::class, 'trackAbandonedCarts'])->hourly();
    
    // Clean expired carts daily
    $schedule->call([CartService::class, 'cleanExpiredCarts'])->daily();
}
```

### Manual Tracking
```php
// Get abandoned carts
$abandonedCarts = CartService::getAbandonedCarts();

// Restore abandoned cart
CartService::restoreAbandonedCart($cartId);
```

---

## Helper Functions (Optional)

Add these to `app/helpers.php` for even easier usage:

```php
if (!function_exists('cart_count')) {
    function cart_count()
    {
        return Cart::getItemCount();
    }
}

if (!function_exists('cart_total')) {
    function cart_total()
    {
        return Cart::getTotal();
    }
}

if (!function_exists('cart_summary')) {
    function cart_summary()
    {
        return Cart::getSummary();
    }
}
```

Then use in Blade:
```php
{{ cart_count() }} items
${{ number_format(cart_total(), 2) }}
```

---

## Error Handling

```php
// Always check the result
$result = Cart::add($productId, $quantity);

if (!$result['success']) {
    // Handle error
    session()->flash('error', $result['message']);
} else {
    // Handle success
    session()->flash('success', $result['message']);
}
```

---

## Performance Tips

1. **Cache cart summary** for frequently accessed data
2. **Use eager loading** when displaying cart items with products
3. **Batch operations** for multiple cart updates
4. **Index database** on frequently queried columns

---

## Extending the Cart

### Custom Methods
```php
// Add to CartService class
public function addToWishlist($productId)
{
    // Custom wishlist logic
}

public function saveForLater($productId)
{
    // Save for later logic
}
```

### Events
```php
// Fire events for cart actions
event(new CartItemAdded($productId, $quantity));
event(new CartUpdated($cartSummary));
```

---

## License
MIT 