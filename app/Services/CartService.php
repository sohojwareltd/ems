<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * CartService - Modern Shopping Cart Implementation
 *
 * Features:
 * - Session-based cart for guests
 * - Database storage for logged-in users
 * - Abandoned cart tracking
 * - Coupon/discount support
 * - Tax calculation
 * - Shipping calculation
 * - Easy frontend integration
 * - Cart expiration management
 *
 * @package App\Services
 */
class CartService
{
    protected $cartId;
    protected $user;
    protected $cart;
    protected $items = [];
    protected $coupons = [];
    protected $taxRate = 0;
    protected $shippingCost = 0;
    protected $abandonedCartThreshold = 24; // hours

    public function __construct()
    {
        $this->user = Auth::user();
        $this->initializeCart();
    }

    /**
     * Initialize cart from session or database
     */
    protected function initializeCart()
    {
        if ($this->user) {
            // Logged in user - use database
            $this->initializeDatabaseCart();
        } else {
            // Guest user - use session
            $this->initializeSessionCart();
        }
    }

    /**
     * Initialize cart for logged-in users
     */
    protected function initializeDatabaseCart()
    {
        $this->cart = Cart::where('user_id', $this->user->id)
            ->where('status', 'active')
            ->first();

        if (!$this->cart) {
            $this->cart = Cart::create([
                'user_id' => $this->user->id,
                'cart_id' => Str::uuid(),
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        $this->cartId = $this->cart->cart_id;
        $this->loadCartItems();
    }

    /**
     * Initialize cart for guest users
     */
    protected function initializeSessionCart()
    {
        $this->cartId = Session::get('cart_id');

        if (!$this->cartId) {
            $this->cartId = Str::uuid();
            Session::put('cart_id', $this->cartId);
        }

        $this->items = Session::get("cart_items_{$this->cartId}", []);
        $this->coupons = Session::get("cart_coupons_{$this->cartId}", []);

        // Debug logging
        Log::info('Cart initialized', [
            'cart_id' => $this->cartId,
            'items_count' => count($this->items),
            'coupons_count' => count($this->coupons),
            'session_id' => Session::getId(),
        ]);
    }

    /**
     * Load cart items from database
     */
    protected function loadCartItems()
    {
        if ($this->cart) {
            $this->items = [];

            foreach ($this->cart->items as $item) {
                // Create unique key based on product and variant
                $itemKey = $this->getItemKey($item->product_id, $item->options ?? [], $item->variant);

                $this->items[$itemKey] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'variant' => $item->variant,
                    'options' => $item->options ?? [],
                    'total' => $item->total,
                    'image_url' => $item->product->image_url ?? null,
                    'added_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            }

            // Load coupons from database
            if ($this->cart->coupon_code && $this->cart->coupon_data) {
                $this->coupons[$this->cart->coupon_code] = $this->cart->coupon_data;
            }
        }
    }

    /**
     * Add product to cart
     *
     * @param int $productId
     * @param int $quantity
     * @param array $options
     * @return array
     */
    public function add($productId, $quantity = 1, $options = [], $variantData = null)
    {
        $product = Product::find($productId);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Handle variants
        $variant = null;
        $sku = $product->is_digital ? 'digital-'.$product->id : $product->sku;
        $price = $product->price;
        $productName = $product->name;

        $isDigital = $product->is_digital ?? false;

        // if ($product->hasVariants() && $variantData) {
        //     // Use the variant data directly from frontend
        //     $variant = $variantData;
        //     $sku = $variant['sku'];
        //     $price = $variant['price'];
        //     $productName = $product->name . ' - ' . implode(', ', array_values($variant['attributes']));
        // } elseif ($product->hasVariants() && !empty($options)) {
        //     // Fallback to finding variant by options
        //     $variant = $this->findVariant($product, $options);

        //     if (!$variant) {
        //         return ['success' => false, 'message' => 'Selected variant not found'];
        //     }

        //     $sku = $variant['sku'];
        //     $price = $variant['price'];
        //     $productName = $product->name . ' - ' . $this->formatVariantName($variant);
        // }

        // DIGITAL PRODUCT: Always quantity 1, skip stock checks
        if ($isDigital) {
            $quantity = 1;
        } else {
            // Check stock for variant
            if ($product->track_quantity && $variant && isset($variant['stock'])) {
                if ($variant['stock'] < $quantity) {
                    $variantLabel = implode(', ', array_values($variant['attributes']));
                    return ['success' => false, 'message' => "Insufficient stock for variant: {$variantLabel} (Available: {$variant['stock']})"];
                }
            } elseif ($product->track_quantity && $product->stock < $quantity) {
                return ['success' => false, 'message' => 'Insufficient stock'];
            }
        }

        // Create unique item key based on product and variant
        $itemKey = $this->getItemKey($productId, $options, $variant);

        if (isset($this->items[$itemKey])) {
            // Update existing item
            if($product->is_digital){
                $this->items[$itemKey]['quantity'] = 1;
            }else{
                $this->items[$itemKey]['quantity'] += $quantity;
            }
            $this->items[$itemKey]['total'] = $price * $this->items[$itemKey]['quantity'];
            $this->items[$itemKey]['image_url'] = $product->image_url; // Update image URL in case it changed
        } else {
                    // Add new item
        $this->items[$itemKey] = [
            'product_id' => $productId,
            'product_name' => $productName,
            'sku' => $sku,
            'price' => $price,
            'quantity' => $quantity,
            'variant' => $variant,
            'options' => $options,
            'total' => $price * $quantity,
            'image_url' => $product->image_url,
            'added_at' => Carbon::now(),
        ];
        }

        $this->saveCart();

        return [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getSubtotal()
        ];
    }

    /**
     * Update product quantity
     *
     * @param int $productId
     * @param int $quantity
     * @param array $options
     * @return array
     */
    public function update($productId, $quantity, $options = [])
    {
        $itemKey = $this->getItemKey($productId, $options);

        if (!isset($this->items[$itemKey])) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }

        $product = Product::find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        $isDigital = $product->is_digital ?? false;
        if ($isDigital) {
            $quantity = 1;
        } else {
            if ($quantity <= 0) {
                return $this->remove($productId, $options);
            }
            // Check stock for variant
            $item = $this->items[$itemKey];
            if ($product->track_quantity && $item['variant'] && isset($item['variant']['stock'])) {
                if ($item['variant']['stock'] < $quantity) {
                    return ['success' => false, 'message' => 'Insufficient stock for selected variant'];
                }
            } elseif ($product->track_quantity && $product->stock < $quantity) {
                return ['success' => false, 'message' => 'Insufficient stock'];
            }
        }

        $this->items[$itemKey]['quantity'] = $quantity;
        $this->items[$itemKey]['total'] = $this->items[$itemKey]['price'] * $quantity;
        $this->items[$itemKey]['updated_at'] = Carbon::now();

        $this->saveCart();

        return [
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getSubtotal(),
            'item_total' => $this->items[$itemKey]['total']
        ];
    }

    /**
     * Update cart item by item ID
     *
     * @param string $itemId
     * @param int $quantity
     * @return array
     */
    public function updateByItemId($itemId, $quantity)
    {
        // Find the item by its key (itemId)
        if (!isset($this->items[$itemId])) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }

        $item = $this->items[$itemId];
        $product = Product::find($item['product_id']);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        $isDigital = $product->is_digital ?? false;
        if ($isDigital) {
            $quantity = 1;
        } else {
            if ($quantity <= 0) {
                return $this->removeByItemId($itemId);
            }

            // Check stock for variant products
            if ($product->track_quantity && $product->hasVariants() && $item['variant'] && isset($item['variant']['stock'])) {
                if ($item['variant']['stock'] < $quantity) {
                    $variantLabel = implode(', ', array_values($item['variant']['attributes'] ?? []));
                    return ['success' => false, 'message' => "Insufficient stock for variant: {$variantLabel} (Available: {$item['variant']['stock']})"];
                }
            } elseif ($product->track_quantity && $product->stock < $quantity) {
                return ['success' => false, 'message' => 'Insufficient stock'];
            }
        }

        $this->items[$itemId]['quantity'] = $quantity;
        $this->items[$itemId]['total'] = $this->items[$itemId]['price'] * $quantity;
        $this->items[$itemId]['updated_at'] = Carbon::now();

        $this->saveCart();

        return [
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getSubtotal(),
            'item_total' => $this->items[$itemId]['total']
        ];
    }

    /**
     * Remove product from cart
     *
     * @param int $productId
     * @param array $options
     * @param array|null $variant
     * @return array
     */
    public function remove($productId, $options = [], $variant = null)
    {
        $itemKey = $this->getItemKey($productId, $options, $variant);

        if (isset($this->items[$itemKey])) {
            unset($this->items[$itemKey]);
            $this->saveCart();
        }

        return [
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getSubtotal()
        ];
    }

    /**
     * Remove cart item by item ID
     *
     * @param string $itemId
     * @return array
     */
    public function removeByItemId($itemId)
    {
        if (isset($this->items[$itemId])) {
            unset($this->items[$itemId]);
            $this->saveCart();
        }

        return [
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $this->getItemCount(),
            'cart_total' => $this->getSubtotal()
        ];
    }

    /**
     * Clear entire cart
     *
     * @return array
     */
    public function clear()
    {
        $this->items = [];
        $this->coupons = [];
        $this->saveCart();

        return [
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0,
            'cart_total' => 0
        ];
    }

    /**
     * Get cart items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get cart item count
     *
     * @return int
     */
    public function getItemCount()
    {
        return array_sum(array_column($this->items, 'quantity'));
    }

    /**
     * Get cart subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return array_sum(array_column($this->items, 'total'));
    }

    /**
     * Get cart total with tax and shipping
     *
     * @return float
     */
    public function getTotal()
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscountTotal();
        $tax = $this->getTaxAmount();
        $shipping = $this->getShippingCost();

        return $subtotal - $discount + $tax + $shipping;
    }

    /**
     * Add coupon to cart
     *
     * @param string $code
     * @return array
     */
    public function addCoupon($code)
    {
        // Implement coupon logic here
        $coupon = \App\Models\Coupon::where('code', $code)
            ->where('is_active', true)
            ->where('starts_at', '<=', Carbon::now())
            ->where('ends_at', '>=', Carbon::now())
            ->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code'];
        }

        // Check if coupon has reached max uses
        if ($coupon->max_uses && $coupon->used >= $coupon->max_uses) {
            return ['success' => false, 'message' => 'Coupon has reached maximum usage limit'];
        }

        // Check minimum order amount
        if ($coupon->min_order && $this->getSubtotal() < $coupon->min_order) {
            return ['success' => false, 'message' => 'Minimum order amount of $' . $coupon->min_order . ' required for this coupon'];
        }

        $this->coupons[$code] = $coupon;
        $this->saveCart();

        // Debug logging
        Log::info('Coupon applied', [
            'code' => $code,
            'coupons_count' => count($this->coupons),
            'cart_id' => $this->cartId,
        ]);

        return ['success' => true, 'message' => 'Coupon applied successfully'];
    }

    /**
     * Remove coupon from cart
     *
     * @param string|null $code
     * @return array
     */
    public function removeCoupon($code = null)
    {
        if ($code === null) {
            // Remove all coupons
            $this->coupons = [];
            $this->saveCart();
            return ['success' => true, 'message' => 'All coupons removed'];
        } else {
            // Remove specific coupon
            if (isset($this->coupons[$code])) {
                unset($this->coupons[$code]);
                $this->saveCart();
            }
            return ['success' => true, 'message' => 'Coupon removed'];
        }
    }

    /**
     * Get discount total
     *
     * @return float
     */
    public function getDiscountTotal()
    {
        $total = 0;
        foreach ($this->coupons as $coupon) {
             $coupon = json_decode($coupon);
            if ($coupon->type === 'percent') {
                $total += ($this->getSubtotal() * $coupon->value / 100);
            } else {
                $total += $coupon->value;
            }
        }
        return $total;
    }

    /**
     * Get discount total (alias for getDiscountTotal)
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->getDiscountTotal();
    }

    /**
     * Set tax rate
     *
     * @param float $rate
     */
    public function setTaxRate($rate)
    {
        $this->taxRate = $rate;
    }

    /**
     * Get tax amount
     *
     * @return float
     */
    public function getTaxAmount()
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscountTotal();
        return ($subtotal - $discount) * ($this->taxRate / 100);
    }

    /**
     * Get tax amount (alias for getTaxAmount)
     *
     * @return float
     */
    public function getTax()
    {
        return $this->getTaxAmount();
    }

    /**
     * Set shipping cost
     *
     * @param float $cost
     */
    public function setShippingCost($cost)
    {
        $this->shippingCost = $cost;
    }

    /**
     * Get shipping cost
     *
     * @return float
     */
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * Get shipping cost (alias for getShippingCost)
     *
     * @return float
     */
    public function getShipping()
    {
        return $this->getShippingCost();
    }

    /**
     * Get cart summary
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'items' => $this->getItems(),
            'item_count' => $this->getItemCount(),
            'subtotal' => $this->getSubtotal(),
            'discount' => $this->getDiscountTotal(),
            'tax' => $this->getTaxAmount(),
            'shipping' => $this->getShippingCost(),
            'total' => $this->getTotal(),
            'coupons' => $this->coupons,
        ];
    }

    /**
     * Get cart object
     *
     * @return Cart|null
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Get coupon data
     *
     * @return array|null
     */
    public function getCoupon()
    {
        return !empty($this->coupons) ? reset($this->coupons) : null;
    }

    /**
     * Apply coupon to cart
     *
     * @param string $code
     * @return array
     */
    public function applyCoupon($code)
    {

        return $this->addCoupon($code);
    }



    /**
     * Save cart to session or database
     */
    protected function saveCart()
    {
        if ($this->user && $this->cart) {

            $this->saveToDatabase();
        } else {
            $this->saveToSession();
        }
    }

    /**
     * Save cart to database
     */
    protected function saveToDatabase()
    {
        // Clear existing items
        $this->cart->items()->delete();

        // Add new items
        foreach ($this->items as $item) {
            $this->cart->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'options' => $item['options'] ?? [],
                'variant' => $item['variant'] ?? null,
                'variant_sku' => $item['variant']['sku'] ?? null,
                'total' => $item['total'],
            ]);
        }

        // Get coupon data
        $couponCode = null;
        $couponData = null;

        if (!empty($this->coupons)) {
            $couponCode = array_keys($this->coupons)[0];
            $couponData = $this->coupons[$couponCode];
        }


        // Update cart
        $this->cart->update([
            'subtotal' => $this->getSubtotal(),
            'discount' => $this->getDiscountTotal(),
            'total' => $this->getTotal(),
            'coupon_code' => $couponCode,
            'coupon_data' => $couponData,
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Save cart to session
     */
    protected function saveToSession()
    {
        Session::put("cart_items_{$this->cartId}", $this->items);
        Session::put("cart_coupons_{$this->cartId}", $this->coupons);

        // Ensure session is saved
        Session::save();

        // Debug logging
        Log::info('Cart saved to session', [
            'cart_id' => $this->cartId,
            'items_count' => count($this->items),
            'coupons_count' => count($this->coupons),
            'session_id' => Session::getId(),
        ]);
    }

    /**
     * Get unique item key
     *
     * @param int $productId
     * @param array $options
     * @param array|null $variant
     * @return string
     */
    protected function getItemKey($productId, $options = [], $variant = null)
    {
        // If we have variant data, use the SKU to create a unique key
        if ($variant && isset($variant['sku'])) {
            return $productId . '_' . $variant['sku'];
        }

        // Fallback to options-based key
        return $productId . '_' . md5(serialize($options));
    }

    /**
     * Merge guest cart with user cart on login
     *
     * @param User $user
     */
    public static function mergeGuestCart(User $user)
    {
        $guestCartId = Session::get('cart_id');
        $guestItems = Session::get("cart_items_{$guestCartId}", []);

        if (empty($guestItems)) {
            return;
        }

        $userCart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userCart) {
            $userCart = Cart::create([
                'user_id' => $user->id,
                'cart_id' => Str::uuid(),
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        // Merge items
        foreach ($guestItems as $item) {
            // Create unique key for comparison
            $itemKey = $item['product_id'] . '_' . ($item['variant']['sku'] ?? md5(serialize($item['options'] ?? [])));

            $existingItem = $userCart->items()
                ->where('product_id', $item['product_id'])
                ->where('variant_sku', $item['variant']['sku'] ?? null)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item['quantity'],
                    'total' => $existingItem->price * ($existingItem->quantity + $item['quantity']),
                ]);
            } else {
                $userCart->items()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'options' => $item['options'] ?? [],
                    'variant' => $item['variant'] ?? null,
                    'variant_sku' => $item['variant']['sku'] ?? null,
                    'total' => $item['total'],
                ]);
            }
        }

        // Clear guest cart
        Session::forget("cart_items_{$guestCartId}");
        Session::forget('cart_id');
    }

    /**
     * Track abandoned carts
     */
    public static function trackAbandonedCarts()
    {
        $threshold = Carbon::now()->subHours(24);

        $abandonedCarts = Cart::where('status', 'active')
            ->where('updated_at', '<', $threshold)
            ->get();

        foreach ($abandonedCarts as $cart) {
            $cart->update(['status' => 'abandoned']);

            // Send abandoned cart email
            if ($cart->user) {
                // Implement email notification
                // Mail::to($cart->user->email)->send(new AbandonedCartMail($cart));
            }
        }
    }

    /**
     * Get abandoned carts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAbandonedCarts()
    {
        return Cart::where('status', 'abandoned')
            ->with(['user', 'items.product'])
            ->get();
    }

    /**
     * Restore abandoned cart
     *
     * @param string $cartId
     * @return bool
     */
    public static function restoreAbandonedCart($cartId)
    {
        $cart = Cart::where('cart_id', $cartId)
            ->where('status', 'abandoned')
            ->first();

        if ($cart) {
            $cart->update(['status' => 'active']);
            return true;
        }

        return false;
    }

    /**
     * Clean expired carts
     */
    public static function cleanExpiredCarts()
    {
        Cart::where('expires_at', '<', Carbon::now())
            ->where('status', 'active')
            ->update(['status' => 'expired']);
    }

    /**
     * Find variant by options
     *
     * @param Product $product
     * @param array $options
     * @return array|null
     */
    protected function findVariant($product, $options)
    {
        if (!$product->variants || !is_array($product->variants)) {
            return null;
        }

        foreach ($product->variants as $variant) {
            if ($this->variantMatchesOptions($variant, $options)) {
                return $variant;
            }
        }

        return null;
    }

    /**
     * Check if variant matches the given options
     *
     * @param array $variant
     * @param array $options
     * @return bool
     */
    protected function variantMatchesOptions($variant, $options)
    {
        // Check if variant has the required options
        foreach ($options as $key => $value) {
            if (!isset($variant[$key]) || $variant[$key] !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Format variant name for display
     *
     * @param array $variant
     * @return string
     */
    protected function formatVariantName($variant)
    {
        $parts = [];

        // Common variant attributes
        $attributes = ['size', 'color', 'material', 'style', 'type'];

        foreach ($attributes as $attr) {
            if (isset($variant[$attr])) {
                $parts[] = ucfirst($attr) . ': ' . $variant[$attr];
            }
        }

        // Add SKU if no other attributes found
        if (empty($parts) && isset($variant['sku'])) {
            $parts[] = 'SKU: ' . $variant['sku'];
        }

        return implode(', ', $parts);
    }

    /**
     * Get available variants for a product
     *
     * @param int $productId
     * @return array
     */
    public function getProductVariants($productId)
    {
        $product = Product::find($productId);

        if (!$product || !$product->hasVariants()) {
            return [];
        }

        return $product->variants ?? [];
    }

    /**
     * Get variant by SKU
     *
     * @param int $productId
     * @param string $sku
     * @return array|null
     */
    public function getVariantBySku($productId, $sku)
    {
        $product = Product::find($productId);

        if (!$product || !$product->hasVariants()) {
            return null;
        }

        foreach ($product->variants as $variant) {
            if (isset($variant['sku']) && $variant['sku'] === $sku) {
                return $variant;
            }
        }

        return null;
    }

    /**
     * Add product by variant SKU
     *
     * @param int $productId
     * @param string $variantSku
     * @param int $quantity
     * @return array
     */
    public function addByVariantSku($productId, $variantSku, $quantity = 1)
    {
        $variant = $this->getVariantBySku($productId, $variantSku);

        if (!$variant) {
            return ['success' => false, 'message' => 'Variant not found'];
        }

        // Convert variant to options
        $options = [];
        $variantAttributes = ['size', 'color', 'material', 'style', 'type'];

        foreach ($variantAttributes as $attr) {
            if (isset($variant[$attr])) {
                $options[$attr] = $variant[$attr];
            }
        }

        return $this->add($productId, $quantity, $options);
    }

    /**
     * Convert cart items to order lines format
     *
     * @return array
     */
    public function toOrderLines()
    {
        $orderLines = [];

        foreach ($this->items as $item) {
            $orderLines[] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
                'variant' => $item['variant'] ?? null,
                'notes' => null,
            ];
        }

        return $orderLines;
    }

    /**
     * Check if cart has items
     *
     * @return bool
     */
    public function hasItems()
    {
        return !empty($this->items);
    }

    /**
     * Get cart items with product information
     *
     * @return array
     */
    public function getItemsWithProducts()
    {
        $items = $this->getItems();
        $productIds = array_column($items, 'product_id');

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($items as &$item) {
            $item['product'] = $products->get($item['product_id']);
        }

        return $items;
    }
}
