<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Models\Order;
use App\Services\PayPalService;
use App\Services\OrderEmailService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;

class PayPalController extends Controller
{
    protected $paypalService;
    protected $checkoutService;

    public function __construct()
    {
        $paypalClientId = setting('payments.paypal_client_id', env('PAYPAL_CLIENT_ID'));
        $paypalSecret = setting('payments.paypal_secret', env('PAYPAL_CLIENT_SECRET'));
        $paypalSandbox = setting('payments.paypal_sandbox', false);
        $this->paypalService = new \App\Services\PayPalService($paypalClientId, $paypalSecret, $paypalSandbox);
        $this->checkoutService = new CheckoutService();
    }

    /**
     * Handle PayPal payment success
     */
    public function success(Request $request)
    {

        try {
            Cart::clear();
            $token = $request->get('token');
            $orderId = $request->get('order');

            Log::info('PayPalController@success', [
                'token' => $token,
                'orderId' => $orderId,
                'query' => $request->query()
            ]);

            if (!$token || !$orderId) {
                Log::error('PayPal success callback missing required parameters', [
                    'token' => $token,
                    'order_id' => $orderId
                ]);

                return redirect()->route('checkout.order-details', $orderId)
                    ->with('error', 'Payment verification failed. Please try again.');
            }

            // Get the order
            $order = Order::find($orderId);
            if (!$order) {
                Log::error('Order not found for PayPal payment', ['order_id' => $orderId]);
                return redirect()->route('checkout.order-details', $orderId)
                    ->with('error', 'Order not found.');
            }

            // Log both local order ID and PayPal payment_intent_id
            Log::info('PayPalController@success - Order loaded', [
                'order_id' => $orderId,
                'paypal_payment_intent_id' => $order->payment_intent_id,
                'token' => $token
            ]);

            // Capture the order using the PayPal Order ID stored in payment_intent_id
            if (!$order->payment_intent_id) {
                Log::error('Order missing PayPal payment_intent_id', ['order_id' => $orderId, 'token' => $token]);
                return redirect()->route('checkout.order-details', $orderId)
                    ->with('error', 'Order is missing PayPal payment information.');
            }
            $result = $this->paypalService->executePayment($order->payment_intent_id, null);

            if ($result['success']) {
                DB::beginTransaction();

                try {
                    // Update order with payment information
                    $order->update([
                        'payment_intent_id' => $result['payment_id'],
                        'payment_status' => $result['payment_status'] == 'paid' ? 'paid' : 'pending',
                        'status' => 'confirmed'
                    ]);

                    // Handle digital products immediately after payment
                    $this->checkoutService->handleDigitalProductsAfterPayment($order);

                    // Send confirmation emails
                    $emailService = new OrderEmailService();
                    $emailService->sendNewOrderEmails($order);

                    DB::commit();

                    // Clear session data
                    Session::forget(['paypal_payment_id', 'paypal_order_id']);

                    // Redirect to success page
                    return redirect()->route('checkout.order-details', $order->id)
                        ->with('success', 'Payment completed successfully!');
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to update order after PayPal payment', [
                        'order_id' => $orderId,
                        'token' => $token,
                        'error' => $e->getMessage()
                    ]);

                    return redirect()->route('checkout.order-details', $orderId)
                        ->with('error', 'Payment completed but order update failed. Please contact support.');
                }
            } else {
                Log::error('PayPal payment capture failed', [
                    'order_id' => $orderId,
                    'token' => $token,
                    'error' => $result['message']
                ]);

                return redirect()->route('checkout.order-details', $orderId)
                    ->with('error', 'Payment failed: ' . $result['message']);
            }
        } catch (Exception $e) {
            Log::error('PayPal success callback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('checkout.order-details', $orderId)
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle PayPal payment cancellation
     */
    public function cancel(Request $request)
    {
        $orderId = Session::get('paypal_order_id');

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'cancelled'
                ]);

                Log::info('PayPal payment cancelled by user', [
                    'order_id' => $orderId,
                    'payment_id' => Session::get('paypal_payment_id')
                ]);
            }
        }

        // Clear session data
        Session::forget(['paypal_payment_id', 'paypal_order_id']);

        return redirect()->route('checkout.order-details', $orderId)
            ->with('error', 'Payment was cancelled.');
    }

    /**
     * Handle PayPal webhook notifications
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $headers = $request->headers->all();

            Log::info('PayPal webhook received', [
                'event_type' => $request->get('event_type'),
                'resource_type' => $request->get('resource_type')
            ]);

            // Verify webhook signature (implement if needed)
            // $this->verifyWebhookSignature($payload, $headers);

            $eventType = $request->get('event_type');
            $resource = $request->get('resource');

            switch ($eventType) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    $this->handlePaymentCompleted($resource);
                    break;

                case 'PAYMENT.CAPTURE.DENIED':
                    $this->handlePaymentDenied($resource);
                    break;

                case 'PAYMENT.CAPTURE.REFUNDED':
                    $this->handlePaymentRefunded($resource);
                    break;

                default:
                    Log::info('Unhandled PayPal webhook event', ['event_type' => $eventType]);
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('PayPal webhook error', [
                'error' => $e->getMessage(),
                'payload' => $request->getContent()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle payment completed webhook
     */
    protected function handlePaymentCompleted($resource)
    {
        $paymentId = $resource['id'] ?? null;

        if ($paymentId) {
            // Find order by payment ID
            $order = Order::where('payment_intent_id', $paymentId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);

                // Handle digital products for webhook payments
                $this->checkoutService->handleDigitalProductsAfterPayment($order);

                Log::info('Order payment confirmed via PayPal webhook', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId
                ]);
            }
        }
    }

    /**
     * Handle payment denied webhook
     */
    protected function handlePaymentDenied($resource)
    {
        $paymentId = $resource['id'] ?? null;

        if ($paymentId) {
            $order = Order::where('payment_intent_id', $paymentId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);

                Log::info('Order payment denied via PayPal webhook', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId
                ]);
            }
        }
    }

    /**
     * Handle payment refunded webhook
     */
    protected function handlePaymentRefunded($resource)
    {
        $paymentId = $resource['id'] ?? null;

        if ($paymentId) {
            $order = Order::where('payment_intent_id', $paymentId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'refunded',
                    'status' => 'refunded'
                ]);

                Log::info('Order payment refunded via PayPal webhook', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId
                ]);
            }
        }
    }
}
