<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show the site review form
     */
    public function create()
    {
        // Only allow logged-in users with active subscriptions
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review.');
        }

        $user = Auth::user();
        
        // Check if user has an active subscription (paid user)
        if (!$user->hasActiveSubscription()) {
            return redirect()->back()->with('error', 'Only active subscribers can submit reviews.');
        }

        return view('frontend.reviews.create');
    }

    /**
     * Store a site review
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review.');
        }

        $user = Auth::user();
        
        // Check if user has an active subscription
        if (!$user->hasActiveSubscription()) {
            return redirect()->back()->with('error', 'Only active subscribers can submit reviews.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'content' => $request->comment,
            'rating' => $request->rating,
            'is_approved' => false,
            'is_active' => true,
        ]);

        return redirect('/')->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }

    /**
     * Show product review form
     */
    public function createProductReview($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review.');
        }

        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // Check if user has purchased this product
        $hasPurchased = $user->orders()
            ->whereHas('orderLines', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        // Check if user has already reviewed this product
        $existingReview = ProductReview::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        return view('frontend.reviews.product', compact('product'));
    }

    /**
     * Store a product review
     */
    public function storeProductReview(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review.');
        }

        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // Check if user has purchased this product
        $order = $user->orders()
            ->whereHas('orderLines', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        // Check if user has already reviewed this product
        $existingReview = ProductReview::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        ProductReview::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'name' => $request->name,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'is_approved' => false,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }
}
