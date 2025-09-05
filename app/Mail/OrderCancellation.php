<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cancellationReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $cancellationReason = null)
    {
        $this->order = $order;
        $this->cancellationReason = $cancellationReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Cancelled - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.cancellation',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->billing_address['first_name'] . ' ' . $this->order->billing_address['last_name'],
                'orderNumber' => $this->order->order_number,
                'orderDate' => $this->order->created_at->format('F j, Y'),
                'cancellationDate' => now()->format('F j, Y'),
                'cancellationReason' => $this->cancellationReason ?? 'Order cancelled by customer request',
                'refundInfo' => $this->getRefundInfo(),
                'items' => $this->order->orderLines,
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
     * Get refund information based on payment method
     */
    private function getRefundInfo(): string
    {
        $paymentMethod = $this->order->payment_method;
        
        $refundInfo = [
            'stripe' => 'Your refund will be processed within 5-10 business days and will appear on your original payment method.',
            'paypal' => 'Your refund will be processed within 3-5 business days and will be credited to your PayPal account.',
        ];

        return $refundInfo[$paymentMethod] ?? 'Your refund will be processed within 5-10 business days.';
    }
} 