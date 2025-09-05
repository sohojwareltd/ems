# Shopping Cart System Documentation

A comprehensive shopping cart system for Laravel with session and database storage, variant support, abandoned cart tracking, and seamless integration with your existing Product, Order, and OrderLine models.

## Features

- **Dual Storage**: Session-based for guests, database for authenticated users
- **Product Variants**: Full support for product variants with SKU, price, and attributes
- **Coupon System**: Apply and manage discount coupons
- **Tax & Shipping**: Configurable tax rates and shipping calculations
- **Abandoned Cart Tracking**: Automatic tracking and recovery
- **Cart Merging**: Seamless merging of guest carts on login
- **Order Integration**: Direct conversion to OrderLine format
- **Modern API**: Clean facade-based interface

## Installation

The cart system is already integrated into your Laravel application. The following components are included:

- `CartService` - Core cart functionality
- `Cart` and `CartItem` models
- Database migrations
- `Cart` facade
- Service provider registration

## Configuration

### Cart Settings

The cart system uses the following configuration (can be added to `config/cart.php`):

```php
return [
    'storage' => env('CART_STORAGE', 'session'), // 'session' or 'database'
    'session_key' => 'cart',
    'expires_after' => 30, // days
    'tax_rate' => 0.10, // 10%
    'shipping_rate' => 5.00, // $5.00
    'currency' => 'USD',
];
```

### Product Variants

Your Product model should have a `variants` JSON column with the following structure:

```php
[
    [
        'sku' => 'PROD-001-RED-L',
        'name' => 'Red Large',
        'price' => 29.99,
        'stock' => 10,
        'size' => 'L',
        'color' => 'Red',
        'weight' => 0.5
    ],
    [
        'sku' => 'PROD-001-BLUE-M',
        'name' => 'Blue Medium',
        'price' => 29.99,
        'stock' => 15,
        'size' => 'M',
        'color' => 'Blue',
        'weight' => 0.4
    ]
]
```

## Usage Examples

### Basic Cart Operations

```php
use App\Facades\Cart;

// Add product to cart
$result = Cart::add(1, 2); // product_id, quantity

// Add product with options (variants)
$result = Cart::add(1, 1, ['size' => 'L', 'color' => 'Red']);

// Add by variant SKU
$result = Cart::addByVariantSku(1, 'PROD-001-RED-L', 1);

// Update quantity
$result = Cart::update(1, 3, ['size' => 'L', 'color' => 'Red']);

// Remove item
$result = Cart::remove(1, ['size' => 'L', 'color' => 'Red']);

// Get cart items
$items = Cart::getItems();

// Get cart totals
$subtotal = Cart::getSubtotal();
$tax = Cart::getTax();
$shipping = Cart::getShipping();
$total = Cart::getTotal();

// Clear cart
Cart::clear();
```

### In Controllers

```php
use App\Facades\Cart;

class ProductController extends Controller
{
    public function addToCart(Request $request)
    {
        $result = Cart::add(
            productId: $request->product_id,
            quantity: $request->quantity,
            options: $request->options ?? []
        );

        if ($result['success']) {
            return response()->json([
                'message' => 'Product added to cart',
                'cart_count' => $result['cart_count'],
                'cart_total' => $result['cart_total']
            ]);
        }

        return response()->json(['error' => $result['message']], 400);
    }

    public function addByVariantSku(Request $request)
    {
        $result = Cart::addByVariantSku(
            productId: $request->product_id,
            variantSku: $request->variant_sku,
            quantity: $request->quantity ?? 1
        );

        return response()->json($result);
    }

    public function updateCart(Request $request)
    {
        $result = Cart::update(
            productId: $request->product_id,
            quantity: $request->quantity,
            options: $request->options ?? []
        );

        return response()->json($result);
    }

    public function removeFromCart(Request $request)
    {
        $result = Cart::remove(
            productId: $request->product_id,
            options: $request->options ?? []
        );

        return response()->json($result);
    }

    public function getCart()
    {
        return response()->json([
            'items' => Cart::getItems(),
            'subtotal' => Cart::getSubtotal(),
            'tax' => Cart::getTax(),
            'shipping' => Cart::getShipping(),
            'discount' => Cart::getDiscount(),
            'total' => Cart::getTotal(),
            'item_count' => Cart::getItemCount()
        ]);
    }

    public function getProductVariants($productId)
    {
        $variants = Cart::getProductVariants($productId);
        return response()->json(['variants' => $variants]);
    }

    public function checkout()
    {
        // Convert cart to order lines
        $orderLines = Cart::toOrderLines();
        
        // Create order with cart data
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'subtotal' => Cart::getSubtotal(),
            'tax' => Cart::getTax(),
            'shipping' => Cart::getShipping(),
            'discount' => Cart::getDiscount(),
            'total' => Cart::getTotal(),
        ]);

        // Create order lines
        foreach ($orderLines as $line) {
            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $line['product_id'],
                'product_name' => $line['product_name'],
                'sku' => $line['sku'],
                'price' => $line['price'],
                'quantity' => $line['quantity'],
                'total' => $line['total'],
                'variant' => $line['variant'],
                'notes' => $line['notes'],
            ]);
        }

        // Clear cart after successful order
        Cart::clear();

        return response()->json(['order_id' => $order->id]);
    }
}
```

### In Livewire Components

```php
use App\Facades\Cart;

class CartComponent extends Component
{
    public function addToCart($productId, $quantity = 1, $options = [])
    {
        $result = Cart::add($productId, $quantity, $options);
        
        if ($result['success']) {
            $this->dispatch('cart-updated', [
                'count' => $result['cart_count'],
                'total' => $result['cart_total']
            ]);
        }
        
        return $result;
    }

    public function addByVariantSku($productId, $variantSku, $quantity = 1)
    {
        $result = Cart::addByVariantSku($productId, $variantSku, $quantity);
        
        if ($result['success']) {
            $this->dispatch('cart-updated', [
                'count' => $result['cart_count'],
                'total' => $result['cart_total']
            ]);
        }
        
        return $result;
    }

    public function updateQuantity($productId, $quantity, $options = [])
    {
        return Cart::update($productId, $quantity, $options);
    }

    public function removeItem($productId, $options = [])
    {
        return Cart::remove($productId, $options);
    }

    public function applyCoupon($code)
    {
        return Cart::applyCoupon($code);
    }

    public function clearCart()
    {
        Cart::clear();
        $this->dispatch('cart-cleared');
    }

    public function getVariants($productId)
    {
        return Cart::getProductVariants($productId);
    }
}
```

### In Blade Views

```php
@php
use App\Facades\Cart;
@endphp

<div class="cart-summary">
    <h3>Cart ({{ Cart::getItemCount() }} items)</h3>
    
    @foreach(Cart::getItems() as $item)
        <div class="cart-item">
            <h4>{{ $item['product_name'] }}</h4>
            <p>SKU: {{ $item['sku'] }}</p>
            <p>Price: ${{ number_format($item['price'], 2) }}</p>
            <p>Quantity: {{ $item['quantity'] }}</p>
            <p>Total: ${{ number_format($item['total'], 2) }}</p>
            
            @if($item['variant'])
                <p>Variant: {{ $item['variant']['name'] ?? 'N/A' }}</p>
                @if(isset($item['variant']['sku']))
                    <p>Variant SKU: {{ $item['variant']['sku'] }}</p>
                @endif
            @endif
        </div>
    @endforeach
    
    <div class="cart-totals">
        <p>Subtotal: ${{ number_format(Cart::getSubtotal(), 2) }}</p>
        <p>Tax: ${{ number_format(Cart::getTax(), 2) }}</p>
        <p>Shipping: ${{ number_format(Cart::getShipping(), 2) }}</p>
        <p>Discount: ${{ number_format(Cart::getDiscount(), 2) }}</p>
        <p><strong>Total: ${{ number_format(Cart::getTotal(), 2) }}</strong></p>
    </div>
</div>

<!-- Product with variants -->
<div class="product-variants">
    @php
        $variants = Cart::getProductVariants($product->id);
    @endphp
    
    @if(!empty($variants))
        <h4>Available Variants:</h4>
        @foreach($variants as $variant)
            <div class="variant-option">
                <button onclick="addVariantToCart({{ $product->id }}, '{{ $variant['sku'] }}')">
                    {{ $variant['name'] ?? $variant['sku'] }} - ${{ number_format($variant['price'], 2) }}
                </button>
            </div>
        @endforeach
    @endif
</div>

<script>
function addVariantToCart(productId, variantSku) {
    Livewire.dispatch('add-by-variant-sku', { 
        productId: productId, 
        variantSku: variantSku 
    });
}
</script>
```

## Available Methods

### Core Cart Operations
- `add($productId, $quantity, $options = [])` - Add product to cart
- `addByVariantSku($productId, $variantSku, $quantity = 1)` - Add by variant SKU
- `update($productId, $quantity, $options = [])` - Update product quantity
- `remove($productId, $options = [])` - Remove product from cart
- `clear()` - Clear entire cart
- `getItems()` - Get all cart items
- `getItemCount()` - Get total number of items
- `hasItems()` - Check if cart has items

### Totals and Calculations
- `getSubtotal()` - Get cart subtotal
- `getTax()` - Get tax amount
- `getShipping()` - Get shipping cost
- `getDiscount()` - Get discount amount
- `getTotal()` - Get final total

### Coupon Management
- `applyCoupon($code)` - Apply discount coupon
- `removeCoupon()` - Remove applied coupon
- `getCoupon()` - Get current coupon

### Variant Support
- `getProductVariants($productId)` - Get available variants for product
- `getVariantBySku($productId, $sku)` - Find variant by SKU

### Order Integration
- `toOrderLines()` - Convert cart to OrderLine format
- `getItemsWithProducts()` - Get items with full product data

### Cart Management
- `mergeGuestCart()` - Merge guest cart on login
- `saveCart()` - Save cart to storage
- `loadCart()` - Load cart from storage

## Cart Item Structure

Each cart item contains:

```php
[
    'product_id' => 1,
    'product_name' => 'Product Name',
    'sku' => 'PROD-001',
    'price' => 29.99,
    'quantity' => 2,
    'total' => 59.98,
    'variant' => [
        'sku' => 'PROD-001-RED-L',
        'name' => 'Red Large',
        'size' => 'L',
        'color' => 'Red'
    ],
    'options' => ['size' => 'L', 'color' => 'Red'],
    'created_at' => '2024-01-01 12:00:00',
    'updated_at' => '2024-01-01 12:00:00'
]
```

## Abandoned Cart Tracking

The system automatically tracks abandoned carts:

```php
// Get abandoned carts
$abandonedCarts = Cart::where('status', 'active')
    ->where('expires_at', '<', now())
    ->get();

// Clean expired carts
Cart::cleanExpiredCarts();
```

## Events

The cart system dispatches events for integration:

- `CartUpdated` - When cart is modified
- `CartCleared` - When cart is cleared
- `CouponApplied` - When coupon is applied
- `CartMerged` - When guest cart is merged on login

## Best Practices

1. **Always check stock**: The system automatically checks stock when adding/updating items
2. **Handle variants properly**: Use the variant helper methods for consistent handling
3. **Validate options**: Ensure variant options match available product variants
4. **Use proper error handling**: Check the success status of cart operations
5. **Clear cart after checkout**: Always clear the cart after successful order creation
6. **Handle guest carts**: The system automatically merges guest carts on login

## Integration with Your Models

The cart system is designed to work seamlessly with your existing models:

- **Product**: Uses `id`, `name`, `price`, `stock`, `variants`, `has_variants`
- **Order**: Creates orders with cart totals
- **OrderLine**: Converts cart items to order lines with variant data
- **User**: Associates carts with authenticated users

## Troubleshooting

### Common Issues

1. **Variant not found**: Ensure the variant exists in the product's variants array
2. **Stock issues**: Check if `track_quantity` is enabled and stock is available
3. **Cart not persisting**: Verify the storage configuration and session setup
4. **Options mismatch**: Ensure variant options exactly match the product variant structure

### Debug Methods

```php
// Check cart contents
dd(Cart::getItems());

// Check specific product variants
dd(Cart::getProductVariants($productId));

// Check cart storage
dd(Cart::getCartId());
``` 