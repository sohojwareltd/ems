<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $previousStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $previousStatus, $newStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusMessages = [
            'pending' => 'Order Received',
            'confirmed' => 'Order Confirmed',
            'processing' => 'Order Processing',
            'shipped' => 'Order Shipped',
            'delivered' => 'Order Delivered',
            'returned' => 'Order Returned',
            'cancelled' => 'Order Cancelled',
            'refunded' => 'Order Refunded',
            'completed' => 'Order Completed',
        ];

        $subject = $statusMessages[$this->newStatus] ?? 'Order Status Update';
        $subject .= ' - Order #' . $this->order->id;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.status-update',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->billing_address['first_name'] . ' ' . $this->order->billing_address['last_name'],
                'orderNumber' => $this->order->id,
                'previousStatus' => $this->previousStatus,
                'newStatus' => $this->newStatus,
                'statusMessage' => $this->getStatusMessage(),
                'trackingNumber' => $this->order->tracking,
                'shippingMethod' => $this->order->shipping_method,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get status-specific message
     */
    private function getStatusMessage(): string
    {
        $messages = [
            'pending' => 'Your order has been received and is awaiting confirmation. We\'ll review your order and confirm it shortly.',
            'confirmed' => 'Great news! Your order has been confirmed and is now being prepared for processing.',
            'processing' => 'Your order is being processed and prepared for shipping. We\'re carefully packaging your items to ensure they arrive in perfect condition.',
            'shipped' => 'Your order has been shipped and is on its way to you! You can track your package using the tracking information provided below.',
            'delivered' => 'Your order has been successfully delivered! We hope you love your new books. Thank you for choosing us.',
            'returned' => 'Your order has been returned. If you have any questions about the return process, please don\'t hesitate to contact us.',
            'cancelled' => 'Your order has been cancelled. If you have any questions or would like to place a new order, please contact our customer service team.',
            'refunded' => 'Your order has been refunded. The refund will be processed within 5-10 business days, depending on your payment method.',
            'completed' => 'Your order has been completed successfully. Thank you for your purchase and we hope to see you again soon!',
        ];

        return $messages[$this->newStatus] ?? 'Your order status has been updated. Please check your order details for more information.';
    }
} 