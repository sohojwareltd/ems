<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $cart = Cart::getSummary();
        $cartItems = Cart::getItemsWithProducts();
        $subtotal = $cart['subtotal'];
        $discount = $cart['discount'];
        $tax = $cart['tax'];
        // $shipping = $cart['shipping'];
        $total = $cart['total'];

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'discount', 'tax', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request): JsonResponse
    {

        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            $result = Cart::add(
                $request->product_id
            );


            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully!',
                    'cart_count' => Cart::getItemCount(),
                    'cart_total' => Cart::getTotal(),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart.',
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $result = Cart::updateByItemId($request->item_id, $request->quantity);

            if ($result['success']) {
                $cartSummary = Cart::getSummary();
                $cartItems = Cart::getItemsWithProducts();

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'cart_count' => Cart::getItemCount(),
                    'cart_total' => Cart::getTotal(),
                    'item_total' => $result['item_total'],
                    'subtotal' => $cartSummary['subtotal'],
                    'discount' => $cartSummary['discount'],
                    'tax' => $cartSummary['tax'],
                    'shipping' => $cartSummary['shipping'],
                    'total' => $cartSummary['total'],
                    'cart_items' => $cartItems,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart.',
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|string',
        ]);

        try {
            $result = Cart::removeByItemId($request->item_id);

            if ($result['success']) {
                $cartSummary = Cart::getSummary();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart!',
                    'cart_count' => Cart::getItemCount(),
                    'cart_total' => Cart::getTotal(),
                    'subtotal' => $cartSummary['subtotal'],
                    'discount' => $cartSummary['discount'],
                    'tax' => $cartSummary['tax'],
                    'shipping' => $cartSummary['shipping'],
                    'total' => $cartSummary['total'],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart.',
            ], 500);
        }
    }

    /**
     * Clear the entire cart
     */
    public function clear(): JsonResponse
    {
        try {
            Cart::clear();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0,
                'cart_total' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart.',
            ], 500);
        }
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        try {
            $result = Cart::applyCoupon($request->coupon_code);

            if ($result['success']) {
                $cartSummary = Cart::getSummary();

                return response()->json([
                    'success' => true,
                    'message' => 'Coupon applied successfully!',
                    'subtotal' => $cartSummary['subtotal'],
                    'discount' => $cartSummary['discount'],
                    'tax' => $cartSummary['tax'],
                    'shipping' => $cartSummary['shipping'],
                    'total' => $cartSummary['total'],
                    'coupon' => Cart::getCoupon(),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon.',
            ], 500);
        }
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(): JsonResponse
    {
        try {
            Cart::removeCoupon();
            $cartSummary = Cart::getSummary();

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully!',
                'subtotal' => $cartSummary['subtotal'],
                'discount' => $cartSummary['discount'],
                'tax' => $cartSummary['tax'],
                'shipping' => $cartSummary['shipping'],
                'total' => $cartSummary['total'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove coupon.',
            ], 500);
        }
    }

    /**
     * Get current cart count (for AJAX polling)
     */
    public function count(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'cart_count' => \App\Facades\Cart::getItemCount(),
        ]);
    }
}
