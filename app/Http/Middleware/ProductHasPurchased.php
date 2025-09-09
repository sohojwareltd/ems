<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductHasPurchased
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $product = $request->product;

        // Check if the user has purchased the product
        $hasPurchased = $user->orders()
            ->whereHas('lines', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            abort(404);
        }
        
        return $next($request);
    }
}
