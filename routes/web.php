<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderPrintController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;
use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');
// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'store'])->name('contact.store');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/model-essays', [PageController::class, 'model'])->name('model.index');
Route::get('/model-essays/{product:slug}', [PageController::class, 'show'])->name('model.show');

// E-commerce Frontend Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
Route::get('/cart/count', [CartController::class, 'count']);

//subscription Routes
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/subscriptions/payment/{id}', [SubscriptionController::class, 'subscriptionsPayment'])->name('subscriptions.payment');
    Route::post('/payment-method/{id}', [SubscriptionController::class, 'paymentMethod'])->name('payment.method');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/order-details/{order}', [CheckoutController::class, 'orderDetails'])->name('checkout.order-details');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::get('/checkout/download-invoice/{order}', [CheckoutController::class, 'downloadInvoice'])->name('checkout.download-invoice');
    Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe.create');
});
Route::get('/checkout/repay/{order}', [CheckoutController::class, 'repay'])->name('checkout.repay')->middleware('auth');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
Route::get('/checkout/summary', [CheckoutController::class, 'getSummary'])->name('checkout.summary');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/repay/process/{order}', [CheckoutController::class, 'repayProcess'])->name('checkout.repay.process')->middleware('auth');

// PayPal Routes
Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
Route::post('/paypal/webhook', [PayPalController::class, 'webhook'])->name('paypal.webhook');

Route::get('/stripe/setup-intent', [SubscriptionController::class, 'getSetupIntent']);



// PayPal Test Route (remove in production)
Route::get('/paypal/test', function () {
    $paypalService = new \App\Services\PayPalService();
    return response()->json($paypalService->testConnection());
})->name('paypal.test');

// Order Routes
Route::get('/orders/{order}', [CheckoutController::class, 'orderDetails'])->name('order.details');

// Print Routes
Route::get('/orders/{order}/print-invoice', [OrderPrintController::class, 'printInvoice'])->name('orders.print-invoice');
Route::get('/orders/{order}/print-shipping-label', [OrderPrintController::class, 'printShippingLabel'])->name('orders.print-shipping-label');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');

Auth::routes();

// User Dashboard Routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('user.password.change');

    // Order Management
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');
    Route::get('/dashbord/products/{product}/download', [UserController::class, 'download'])
        ->middleware('product.has.purchased')
        ->name('user.products.download');

    Route::get('/dashboard/subscriptions', [SubscriptionController::class, 'subscriptions'])->name('user.subscription');
    // Cancel a subscription
    Route::post('/dashboard/subscription/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('user.subscription.cancel');

    // Set a subscription as default
    Route::post('/dashboard/subscription/{subscription}/set-default', [SubscriptionController::class, 'setDefault'])->name('user.subscription.set-default');

    Route::get('/dashboard/audiobooks', [\App\Http\Controllers\UserAudioBookController::class, 'index'])->name('user.audiobooks');
    Route::get('/dashboard/audiobooks/{audiobook}/stream', [\App\Http\Controllers\UserAudioBookController::class, 'stream'])->name('user.audiobooks.stream');
    Route::get('/dashboard/audiobooks/{audiobook}/download', [\App\Http\Controllers\UserAudioBookController::class, 'download'])->name('user.audiobooks.download');
    Route::get('/dashboard/audiobooks/{audiobook}/download-zip', [\App\Http\Controllers\UserAudioBookController::class, 'downloadZip'])->name('user.audiobooks.download-zip');
    Route::get('/audiobooks/{audiobook}/trial-stream', [\App\Http\Controllers\UserAudioBookController::class, 'trialStream'])->name('audiobooks.trial.stream');
});

Route::get('/order-confirmation/{order}', function (Order $order) {
    return new OrderConfirmation($order);
})->name('order.confirmation.email');

// test routes for email notifications
// Uncomment these routes to test email notifications
// Route::get('/test/order-confirmation/{order}', function (Order $order) {
//     return new OrderConfirmation($order);
// });

// Route::get('/test/order-cancellation/{order}', function (Order $order) {
//     return new OrderCancellation($order, 'Customer cancelled due to delay');
// });


// Route::get('/test/order-refund/{order}', function (Order $order) {
//     return new OrderRefund($order, 50.00, 'Partial refund issued');
// });

// Route::get('/test/order-status-update/{order}', function (Order $order) {
//     return new OrderStatusUpdate($order, 'processing', 'shipped');
// });


// Route::get('/test/new-order-notification/{order}', function (Order $order) {
//     $billing = $order->billing_address ?? [];
//     $shipping = $order->shipping_address ?? [];

//     return view('emails.admin.new-order', [
//         'order'           => $order,
//         'orderNumber'     => $order->order_number,
//         'orderDate'       => $order->created_at->format('F j, Y'),
//         'total'           => number_format($order->total, 2),
//         'itemCount'       => $order->orderLines->count(),
//         'paymentMethod'   => $order->payment_method,
//         'customerName'    => $billing['first_name'] . ' ' . $billing['last_name'],
//         'customerEmail'   => $billing['email'] ?? '',
//         'customerPhone'   => $billing['phone'] ?? '',
//         'billingAddress'  => $billing,
//         'shippingAddress' => $shipping,
//         'items'           => $order->orderLines,
//         'adminUrl'        => url('/admin/orders/' . $order->id),
//     ]);
// });

// Route::get('/send-test-new-order/{order}', function (Order $order) {
//     Mail::to('test@example.com')->send(new NewOrderNotification($order));
//     return 'New order notification email sent!';
// });

Route::get('/test-welcome-email', function () {
    $user = Auth::user() ?? \App\Models\User::first(); // fallback to first user
    if (!$user) {
        abort(404, 'No user found to send test email.');
    }
    Mail::to($user->email)->send(new WelcomeEmail($user));
    return 'Welcome email sent to ' . $user->email;
});

Route::get('/test-order-confirmation/{order}', function (Order $order) {
    Mail::to('test@example.com')->send(new OrderConfirmation($order));
    Mail::to('test@example.com')->send(new NewOrderNotification($order));
    return 'New order notification email sent!';
})->name('test.order-confirmation');
