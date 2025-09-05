<?php

namespace App\Services;

use App\Models\Order;
use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdate;
use App\Mail\ShippingConfirmation;
use App\Mail\OrderCancellation;
use App\Mail\OrderRefund;
use App\Mail\NewOrderNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderEmailService
{
    /**
     * Send order confirmation email to customer
     * 
     * @param Order $order
     * @return bool
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        try {
            Mail::to($order->billing_address['email'])
                ->send(new OrderConfirmation($order));

            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->billing_address['email']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->billing_address['email'],
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send order status update email to customer
     * 
     * @param Order $order
     * @param string $previousStatus
     * @param string $newStatus
     * @return bool
     */
    public function sendOrderStatusUpdate(Order $order, string $previousStatus, string $newStatus): bool
    {
        try {
            Mail::to($order->billing_address['email'])
                ->send(new OrderStatusUpdate($order, $previousStatus, $newStatus));

            Log::info('Order status update email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'customer_email' => $order->billing_address['email']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'customer_email' => $order->billing_address['email'],
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send shipping confirmation email to customer
     * 
     * @param Order $order
     * @return bool
     */
    public function sendShippingConfirmation(Order $order): bool
    {
        try {
            Mail::to($order->billing_address['email'])
                ->send(new ShippingConfirmation($order));

            Log::info('Shipping confirmation email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_number' => $order->shipping_tracking,
                'customer_email' => $order->billing_address['email']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send shipping confirmation email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_number' => $order->shipping_tracking,
                'customer_email' => $order->billing_address['email'],
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send order cancellation email to customer
     * 
     * @param Order $order
     * @param string|null $cancellationReason
     * @return bool
     */
    public function sendOrderCancellation(Order $order, ?string $cancellationReason = null): bool
    {
        try {
            Mail::to($order->billing_address['email'])
                ->send(new OrderCancellation($order, $cancellationReason));

            Log::info('Order cancellation email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'cancellation_reason' => $cancellationReason,
                'customer_email' => $order->billing_address['email']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order cancellation email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'cancellation_reason' => $cancellationReason,
                'customer_email' => $order->billing_address['email'],
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send order refund email to customer
     * 
     * @param Order $order
     * @param float|null $refundAmount
     * @param string|null $refundReason
     * @return bool
     */
    public function sendOrderRefund(Order $order, ?float $refundAmount = null, ?string $refundReason = null): bool
    {
        try {
            Mail::to($order->billing_address['email'])
                ->send(new OrderRefund($order, $refundAmount, $refundReason));

            Log::info('Order refund email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'refund_amount' => $refundAmount,
                'refund_reason' => $refundReason,
                'customer_email' => $order->billing_address['email']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order refund email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'refund_amount' => $refundAmount,
                'refund_reason' => $refundReason,
                'customer_email' => $order->billing_address['email'],
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send new order notification email to admin
     * 
     * @param Order $order
     * @return bool
     */
    public function sendNewOrderNotification(Order $order): bool
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@yourstore.com');
            
            Mail::to($adminEmail)
                ->send(new NewOrderNotification($order));

            Log::info('New order notification email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'admin_email' => $adminEmail
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send new order notification email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'admin_email' => config('mail.admin_email'),
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send all appropriate emails for a new order
     * 
     * @param Order $order
     * @return array
     */
    public function sendNewOrderEmails(Order $order): array
    {
        $results = [
            'customer_confirmation' => $this->sendOrderConfirmation($order),
            'admin_notification' => $this->sendNewOrderNotification($order)
        ];

        return $results;
    }

    /**
     * Send appropriate emails when order status changes
     * 
     * @param Order $order
     * @param string $previousStatus
     * @param string $newStatus
     * @return array
     */
    public function sendStatusChangeEmails(Order $order, string $previousStatus, string $newStatus): array
    {
        $results = [
            'status_update' => $this->sendOrderStatusUpdate($order, $previousStatus, $newStatus)
        ];

        // Send shipping confirmation when order is shipped
        if ($newStatus === 'shipped' && $previousStatus !== 'shipped') {
            $results['shipping_confirmation'] = $this->sendShippingConfirmation($order);
        }

        return $results;
    }

    /**
     * Send appropriate emails when order is cancelled
     * 
     * @param Order $order
     * @param string|null $cancellationReason
     * @return array
     */
    public function sendCancellationEmails(Order $order, ?string $cancellationReason = null): array
    {
        $results = [
            'cancellation' => $this->sendOrderCancellation($order, $cancellationReason)
        ];

        return $results;
    }

    /**
     * Send appropriate emails when order is refunded
     * 
     * @param Order $order
     * @param float|null $refundAmount
     * @param string|null $refundReason
     * @return array
     */
    public function sendRefundEmails(Order $order, ?float $refundAmount = null, ?string $refundReason = null): array
    {
        $results = [
            'refund' => $this->sendOrderRefund($order, $refundAmount, $refundReason)
        ];

        return $results;
    }

    /**
     * Test email functionality
     * 
     * @param Order $order
     * @param string $emailType
     * @return bool
     */
    public function testEmail(Order $order, string $emailType): bool
    {
        switch ($emailType) {
            case 'confirmation':
                return $this->sendOrderConfirmation($order);
            case 'status_update':
                return $this->sendOrderStatusUpdate($order, 'pending', 'processing');
            case 'shipping':
                return $this->sendShippingConfirmation($order);
            case 'cancellation':
                return $this->sendOrderCancellation($order, 'Test cancellation');
            case 'refund':
                return $this->sendOrderRefund($order, 50.00, 'Test refund');
            case 'admin_notification':
                return $this->sendNewOrderNotification($order);
            default:
                Log::error('Unknown email type for testing', ['email_type' => $emailType]);
                return false;
        }
    }
} 