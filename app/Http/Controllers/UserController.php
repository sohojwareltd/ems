<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Get user statistics
        $stats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'processing_orders' => $user->orders()->where('status', 'processing')->count(),
            'cancelled_orders' => $user->orders()->where('status', 'cancelled')->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total'),
        ];

        // Get recent orders
        $recent_orders = $user->orders()
            ->with(['lines.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recent_orders'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($request->only([
            'name',
            'lastname',
            'phone',
            'address',
            'city',
            'state',
            'zip',
            'country'
        ]));

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('user.profile')->with('success', 'Password changed successfully!');
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['lines.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['lines.product', 'discounts']);

        return view('user.orders.show', compact('order'));
    }

    public function downloadOrder()
    {
        $products = Product::with('qualiification','subject', 'examboard', 'resource')
            ->whereHas('orderLines.order', function ($query)  {
                $query->where('user_id', Auth::id())
                    ->where('status', 'completed');
            })
            ->paginate(15);

        return view('user.orders.download', compact('products'));
    }


    public function download(Product $product)
    {
        return response()->download(Storage::path($product->ppt_file), $product->name . '.pptx');
    }
}
