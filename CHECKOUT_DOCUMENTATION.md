# Checkout Service Documentation

A comprehensive checkout service for Laravel that handles the entire checkout process with seamless integration between Cart, Order creation, and payment processing (Stripe and PayPal).

## Features

- **Single Function Checkout**: Process complete checkout with one function call
- **Cart Integration**: Seamless integration with the Cart system
- **Payment Processing**: Stripe and PayPal integration
- **Order Management**: Automatic order and order line creation
- **Validation**: Comprehensive validation of cart, stock, and checkout data
- **Coupon Support**: Apply and validate discount coupons
- **Address Management**: Separate billing and shipping addresses
- **Admin Shipping Management**: Admin-controlled shipping method selection and tracking
- **Error Handling**: Robust error handling with rollback on failure
- **Email Notifications**: Automatic confirmation emails
- **Transaction Safety**: Database transactions ensure data integrity

## Installation

The checkout service is already integrated into your Laravel application. The following components are included:

- `CheckoutService` - Core checkout functionality
- `Checkout` facade - Easy access to checkout methods
- Service provider registration

## Configuration

### Payment Gateway Configuration

Add the following to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_stripe_public_key
STRIPE_SECRET=sk_test_your_stripe_secret_key

# PayPal Configuration
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox  # or 'live' for production
```

### Services Configuration

Add to `config/services.php`:

```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],

'paypal' => [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'mode' => env('PAYPAL_MODE', 'sandbox'),
],
```

## Basic Usage

### Single Function Checkout

```php
use App\Facades\Checkout;

// Prepare checkout data
$checkoutData = [
    'billing_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'country' => 'US'
    ],
    'shipping_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'country' => 'US'
    ],
    'payment_method' => 'stripe',
    'payment_token' => 'pm_card_visa', // Stripe payment method token
    'coupon_code' => 'SAVE10', // Optional
    'notes' => 'Please deliver after 6 PM' // Optional
];

// Process checkout
$result = Checkout::processCheckout($checkoutData, 'stripe');

if ($result['success']) {
    // Order created successfully
    echo "Order placed! Order #: " . $result['order_number'];
} else {
    // Handle error
    echo "Checkout failed: " . $result['message'];
}
```

## Detailed Usage Examples

### In Controllers

```php
use App\Facades\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        $summary = Checkout::getCheckoutSummary();
        $paymentMethods = Checkout::getPaymentMethods();

        return view('checkout.index', compact('summary', 'paymentMethods'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'billing_address.first_name' => 'required|string|max:255',
            'billing_address.last_name' => 'required|string|max:255',
            'billing_address.email' => 'required|email',
            'billing_address.phone' => 'required|string|max:20',
            'billing_address.address' => 'required|string|max:500',
            'billing_address.city' => 'required|string|max:255',
            'billing_address.state' => 'required|string|max:255',
            'billing_address.zip' => 'required|string|max:20',
            'billing_address.country' => 'required|string|max:255',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'required|string|max:255',
            'shipping_address.address' => 'required|string|max:500',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.zip' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:255',

            'payment_method' => 'required|string',
            'payment_token' => 'required|string',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $checkoutData = $request->all();
        $result = Checkout::processCheckout($checkoutData, $request->payment_method);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'order_id' => $result['order_id'],
                'order_number' => $result['order_number'],
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $result = Checkout::applyCoupon($request->coupon_code);

        return response()->json($result);
    }

    public function removeCoupon()
    {
        $result = Checkout::removeCoupon();
        return response()->json($result);
    }

    public function getCheckoutSummary()
    {
        $summary = Checkout::getCheckoutSummary();
        return response()->json($summary);
    }
}
```

### In Livewire Components

```php
use App\Facades\Checkout;

class CheckoutComponent extends Component
{
    public $billingAddress = [];
    public $shippingAddress = [];

    public $paymentMethod = 'stripe';
    public $paymentToken = '';
    public $couponCode = '';
    public $notes = '';
    public $sameAsBilling = true;

    public function mount()
    {
        $this->billingAddress = [
            'first_name' => auth()->user()->first_name ?? '',
            'last_name' => auth()->user()->last_name ?? '',
            'email' => auth()->user()->email ?? '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => 'US'
        ];

        $this->shippingAddress = $this->billingAddress;
    }

    public function updatedSameAsBilling($value)
    {
        if ($value) {
            $this->shippingAddress = $this->billingAddress;
        }
    }

    public function applyCoupon()
    {
        $result = Checkout::applyCoupon($this->couponCode);
        
        if ($result['success']) {
            $this->dispatch('coupon-applied', $result);
        } else {
            $this->addError('coupon_code', $result['message']);
        }
    }

    public function removeCoupon()
    {
        $result = Checkout::removeCoupon();
        $this->couponCode = '';
        $this->dispatch('coupon-removed', $result);
    }

    public function processCheckout()
    {
        $this->validate([
            'billingAddress.first_name' => 'required',
            'billingAddress.last_name' => 'required',
            'billingAddress.email' => 'required|email',
            'billingAddress.phone' => 'required',
            'billingAddress.address' => 'required',
            'billingAddress.city' => 'required',
            'billingAddress.state' => 'required',
            'billingAddress.zip' => 'required',
            'billingAddress.country' => 'required',
            'shippingAddress.first_name' => 'required',
            'shippingAddress.last_name' => 'required',
            'shippingAddress.address' => 'required',
            'shippingAddress.city' => 'required',
            'shippingAddress.state' => 'required',
            'shippingAddress.zip' => 'required',
            'shippingAddress.country' => 'required',
            'paymentToken' => 'required',
        ]);

        $checkoutData = [
            'billing_address' => $this->billingAddress,
            'shipping_address' => $this->shippingAddress,

            'payment_method' => $this->paymentMethod,
            'payment_token' => $this->paymentToken,
            'coupon_code' => $this->couponCode,
            'notes' => $this->notes,
        ];

        $result = Checkout::processCheckout($checkoutData, $this->paymentMethod);

        if ($result['success']) {
            $this->dispatch('checkout-successful', $result);
            return redirect()->route('order.confirmation', $result['order_id']);
        } else {
            $this->addError('checkout', $result['message']);
        }
    }

    public function render()
    {
        $summary = Checkout::getCheckoutSummary();

        $paymentMethods = Checkout::getPaymentMethods();

        return view('livewire.checkout', compact('summary', 'paymentMethods'));
    }
}
```

### In Blade Views

```php
@php
use App\Facades\Checkout;
$summary = Checkout::getCheckoutSummary();
$paymentMethods = Checkout::getPaymentMethods();
@endphp

<div class="checkout-page">
    <!-- Order Summary -->
    <div class="order-summary">
        <h3>Order Summary</h3>
        @foreach($summary['items'] as $item)
            <div class="cart-item">
                <h4>{{ $item['product_name'] }}</h4>
                <p>SKU: {{ $item['sku'] }}</p>
                <p>Price: ${{ number_format($item['price'], 2) }}</p>
                <p>Quantity: {{ $item['quantity'] }}</p>
                <p>Total: ${{ number_format($item['total'], 2) }}</p>
                
                @if($item['variant'])
                    <p>Variant: {{ $item['variant']['name'] ?? 'N/A' }}</p>
                @endif
            </div>
        @endforeach
        
        <div class="totals">
            <p>Subtotal: ${{ number_format($summary['subtotal'], 2) }}</p>
            <p>Tax: ${{ number_format($summary['tax'], 2) }}</p>
            <p>Shipping: ${{ number_format($summary['shipping'], 2) }}</p>
            @if($summary['discount'] > 0)
                <p>Discount: -${{ number_format($summary['discount'], 2) }}</p>
            @endif
            <p><strong>Total: ${{ number_format($summary['total'], 2) }}</strong></p>
        </div>
    </div>

    <!-- Checkout Form -->
    <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        
        <!-- Billing Address -->
        <div class="billing-address">
            <h3>Billing Address</h3>
            <input type="text" name="billing_address[first_name]" placeholder="First Name" required>
            <input type="text" name="billing_address[last_name]" placeholder="Last Name" required>
            <input type="email" name="billing_address[email]" placeholder="Email" required>
            <input type="tel" name="billing_address[phone]" placeholder="Phone" required>
            <input type="text" name="billing_address[address]" placeholder="Address" required>
            <input type="text" name="billing_address[city]" placeholder="City" required>
            <input type="text" name="billing_address[state]" placeholder="State" required>
            <input type="text" name="billing_address[zip]" placeholder="ZIP Code" required>
            <select name="billing_address[country]" required>
                <option value="US">United States</option>
                <option value="CA">Canada</option>
                <!-- Add more countries -->
            </select>
        </div>

        <!-- Shipping Address -->
        <div class="shipping-address">
            <h3>Shipping Address</h3>
            <label>
                <input type="checkbox" id="same-as-billing"> Same as billing address
            </label>
            <div id="shipping-fields">
                <input type="text" name="shipping_address[first_name]" placeholder="First Name" required>
                <input type="text" name="shipping_address[last_name]" placeholder="Last Name" required>
                <input type="text" name="shipping_address[address]" placeholder="Address" required>
                <input type="text" name="shipping_address[city]" placeholder="City" required>
                <input type="text" name="shipping_address[state]" placeholder="State" required>
                <input type="text" name="shipping_address[zip]" placeholder="ZIP Code" required>
                <select name="shipping_address[country]" required>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                </select>
            </div>
        </div>

        <!-- Shipping Method - Admin will choose this -->
        <div class="shipping-info">
            <h3>Shipping Information</h3>
            <p><em>Shipping method and cost will be determined by our team when processing your order.</em></p>
        </div>

        <!-- Payment Method -->
        <div class="payment-method">
            <h3>Payment Method</h3>
            @foreach($paymentMethods as $method)
                <label>
                    <input type="radio" name="payment_method" value="{{ $method['id'] }}" 
                           {{ $method['id'] === 'stripe' ? 'checked' : '' }}>
                    {{ $method['name'] }}
                    <small>{{ $method['description'] }}</small>
                </label>
            @endforeach
            
            <!-- Stripe Elements will be inserted here -->
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
        </div>

        <!-- Coupon Code -->
        <div class="coupon-code">
            <h3>Coupon Code</h3>
            <input type="text" name="coupon_code" placeholder="Enter coupon code">
            <button type="button" onclick="applyCoupon()">Apply</button>
        </div>

        <!-- Order Notes -->
        <div class="order-notes">
            <h3>Order Notes</h3>
            <textarea name="notes" placeholder="Special instructions for delivery"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="checkout-button">Place Order</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Stripe integration
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

card.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

document.getElementById('checkout-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    stripe.createPaymentMethod({
        type: 'card',
        card: card,
    }).then(function(result) {
        if (result.error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            // Add payment method token to form
            const form = document.getElementById('checkout-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_token');
            hiddenInput.setAttribute('value', result.paymentMethod.id);
            form.appendChild(hiddenInput);
            
            // Submit form
            form.submit();
        }
    });
});

function applyCoupon() {
    const couponCode = document.querySelector('input[name="coupon_code"]').value;
    
    fetch('/checkout/apply-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_code: couponCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Coupon applied successfully!');
            location.reload();
        } else {
            alert('Coupon error: ' + data.message);
        }
    });
}
</script>
```

## Available Methods

### Core Checkout
- `processCheckout($checkoutData, $paymentMethod)` - Process complete checkout
- `getCheckoutSummary()` - Get current cart summary
- `applyCoupon($couponCode)` - Apply discount coupon
- `removeCoupon()` - Remove applied coupon

### Configuration
- `getAdminShippingMethods()` - Get available shipping options for admin
- `getPaymentMethods()` - Get available payment methods

## Admin Shipping Management

Since customers don't choose shipping methods during checkout, admins have full control over shipping method selection and tracking number assignment:

### Available Shipping Methods
```php
$shippingMethods = Checkout::getAdminShippingMethods();
// Returns:
[
    [
        'id' => 'standard',
        'name' => 'Standard Shipping',
        'price' => 5.00,
        'delivery_time' => '3-5 business days'
    ],
    [
        'id' => 'express',
        'name' => 'Express Shipping',
        'price' => 15.00,
        'delivery_time' => '1-2 business days'
    ],
    [
        'id' => 'overnight',
        'name' => 'Overnight Shipping',
        'price' => 25.00,
        'delivery_time' => 'Next business day'
    ],
    [
        'id' => 'free',
        'name' => 'Free Shipping',
        'price' => 0.00,
        'delivery_time' => '5-7 business days'
    ]
]
```

### Update Order Shipping (Admin)
```php
// In your admin order management
$order = Order::find($orderId);

// Update shipping method, cost, and tracking
$order->update([
    'shipping_method' => 'express',
    'shipping' => 15.00,
    'shipping_tracking' => '1Z999AA1234567890', // Tracking number
    'total' => $order->subtotal + $order->tax + 15.00 - $order->discount
]);

// Send shipping confirmation email to customer
// Mail::to($order->billing_address['email'])->send(new ShippingConfirmation($order));
```

## Checkout Data Structure

The `$checkoutData` array should contain:

```php
[
    'billing_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'country' => 'US'
    ],
    'shipping_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'country' => 'US'
    ],

    'payment_method' => 'stripe', // stripe, paypal
    'payment_token' => 'pm_card_visa', // Payment method token from Stripe/PayPal
    'coupon_code' => 'SAVE10', // Optional
    'notes' => 'Delivery instructions' // Optional
]
```

## Payment Integration

### Stripe Integration

1. Install Stripe PHP SDK:
```bash
composer require stripe/stripe-php
```

2. Configure Stripe in your `.env`:
```env
STRIPE_KEY=pk_test_your_public_key
STRIPE_SECRET=sk_test_your_secret_key
```

3. Use Stripe Elements for frontend:
```javascript
const stripe = Stripe('your_publishable_key');
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');
```

### PayPal Integration

1. Install PayPal SDK:
```bash
composer require paypal/rest-api-sdk-php
```

2. Configure PayPal in your `.env`:
```env
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_MODE=sandbox
```

## Order Flow

1. **Validation**: Check cart items, stock availability, and required fields
2. **Order Creation**: Generate order number and create order record (shipping method = null, shipping cost = 0)
3. **Payment Processing**: Process payment with selected gateway
4. **Order Lines**: Create order lines from cart items
5. **Cart Clear**: Clear the cart after successful order
6. **Email Notifications**: Send confirmation emails
7. **Database Commit**: Commit all changes
8. **Admin Processing**: Admin selects shipping method and adds tracking number

## Error Handling

The service includes comprehensive error handling:

- **Validation Errors**: Field validation, stock checks, coupon validation
- **Payment Errors**: Card errors, insufficient funds, network issues
- **Database Errors**: Transaction rollback on failure
- **Logging**: All errors are logged for debugging

## Best Practices

1. **Always validate input**: Use Laravel validation rules
2. **Handle payment errors**: Display user-friendly error messages
3. **Use transactions**: Ensure data consistency
4. **Log everything**: Track all checkout attempts and errors
5. **Test thoroughly**: Test with Stripe test cards and PayPal sandbox
6. **Security**: Never store payment tokens, use them immediately
7. **User experience**: Provide clear feedback and progress indicators
8. **Admin shipping management**: Update shipping method and tracking promptly
9. **Customer communication**: Notify customers when shipping details are updated

## Troubleshooting

### Common Issues

1. **Payment failed**: Check Stripe/PayPal configuration and test credentials
2. **Stock issues**: Verify product stock levels and tracking settings
3. **Validation errors**: Ensure all required fields are provided
4. **Cart empty**: Check if cart has items before checkout
5. **Coupon errors**: Verify coupon validity and usage limits
6. **Shipping not set**: Ensure admin sets shipping method and tracking after order placement
7. **Order total mismatch**: Recalculate total after shipping method is updated

### Debug Methods

```php
// Check checkout summary
dd(Checkout::getCheckoutSummary());

// Test payment processing
$testData = [/* test checkout data */];
$result = Checkout::processCheckout($testData, 'stripe');
dd($result);

// Check available methods
dd(Checkout::getPaymentMethods());
dd(Checkout::getAdminShippingMethods());
```

## Integration with Your Models

The checkout service works seamlessly with your existing models:

- **Cart**: Uses Cart facade for cart operations
- **Order**: Creates orders with proper structure (shipping method = null initially)
- **OrderLine**: Converts cart items to order lines
- **Coupon**: Validates and applies discount coupons
- **User**: Associates orders with authenticated users
- **Product**: Checks stock availability and product information

## Key Benefits of Admin-Controlled Shipping

1. **Flexibility**: Admin can choose the most cost-effective shipping method
2. **Customer Service**: Can offer free shipping for VIP customers or large orders
3. **Tracking Integration**: Easy to add tracking numbers after shipping labels are generated
4. **Cost Control**: Admin can adjust shipping costs based on actual carrier rates
5. **Simplified Checkout**: Customers don't need to make shipping decisions during checkout
6. **Order Management**: Centralized control over all shipping decisions 