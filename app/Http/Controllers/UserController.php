<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\PasswordChangeRequest;
use App\Models\EmailChangeRequest;
use App\Mail\VerifyPasswordChange;
use App\Mail\VerifyEmailChange;
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

        // Generate verification token
        $token = Str::random(60);
        $user = Auth::user();
        
        // Store the new password with token for verification
        PasswordChangeRequest::updateOrCreate(
            ['user_id' => $user->id],
            [
                'new_password' => Hash::make($request->password),
                'token' => $token,
            ]
        );

        // Send verification email
        Mail::to($user->email)->send(new VerifyPasswordChange(
            $user,
            $token,
            route('user.password.verify', ['token' => $token])
        ));

        return redirect()->route('user.profile')->with('success', 'A verification email has been sent to your email address. Please verify to complete the password change.');
    }

    public function verifyPasswordChange($token)
    {
        $request = PasswordChangeRequest::where('token', $token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$request) {
            return redirect()->route('user.profile')->with('error', 'Invalid or expired verification token.');
        }

        $user = Auth::user();
        if ($request->user_id !== $user->id) {
            return redirect()->route('user.profile')->with('error', 'This verification token is not for your account.');
        }

        // Update password
        $user->update(['password' => $request->new_password]);
        
        // Delete the request
        $request->delete();

        return redirect()->route('user.profile')->with('success', 'Your password has been changed successfully!');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email|different:email',
            'current_password' => 'required|current_password',
        ]);

        // Generate verification token
        $token = Str::random(60);
        $user = Auth::user();
        
        // Store the new email with token for verification (reset timestamp each time)
        EmailChangeRequest::where('user_id', $user->id)->delete();
        EmailChangeRequest::create([
            'user_id' => $user->id,
            'new_email' => $request->new_email,
            'token' => $token,
        ]);

        // Send verification email to NEW email address
        Mail::to($request->new_email)->send(new VerifyEmailChange(
            $user,
            $request->new_email,
            $token,
            route('user.email.verify', ['token' => $token])
        ));

        return redirect()->route('user.profile')->with('success', 'A verification email has been sent to your new email address. Please verify to complete the email change.');
    }

    public function verifyEmailChange($token)
    {
        $request = EmailChangeRequest::where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$request) {
            return redirect()->route('user.profile')->with('error', 'Invalid or expired verification token.');
        }

        $user = Auth::user();
        if ($request->user_id !== $user->id) {
            return redirect()->route('user.profile')->with('error', 'This verification token is not for your account.');
        }

        // Update email and mark verified
        $user->update([
            'email' => $request->new_email,
            'email_verified_at' => now(),
        ]);
        
        // Delete the request
        $request->delete();

        return redirect()->route('user.profile')->with('success', 'Your email has been changed successfully!');
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

    public function downloadOrder(Request $request)
    {
        $products = Product::with('qualiification','subject', 'examboard', 'resource')
            ->whereHas('orderLines.order', function ($query)  {
                $query->where('user_id', Auth::id())
                    ->where('status', 'completed');
            })
            ->paginate(15);

        

             if ($request->has('search') && $request->search) {
            $search = $request->search;
            $products->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return view('user.orders.download', compact('products'));
    }


    public function download(Product $product)
    {
        return response()->download(Storage::path($product->ppt_file), $product->name . '.pptx');
    }
}
