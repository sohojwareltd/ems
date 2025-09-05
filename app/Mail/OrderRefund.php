<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderRefund extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $refundAmount;
    public $refundReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $refundAmount = null, $refundReason = null)
    {
        $this->order = $order;
        $this->refundAmount = $refundAmount ?? $order->total;
        $this->refundReason = $refundReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund Processed - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.refund',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->billing_address['first_name'] . ' ' . $this->order->billing_address['last_name'],
                'orderNumber' => $this->order->order_number,
                'orderDate' => $this->order->created_at->format('F j, Y'),
                'refundDate' => now()->format('F j, Y'),
                'refundAmount' => number_format($this->refundAmount, 2),
                'refundReason' => $this->refundReason ?? 'Customer request',
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
            'stripe' => 'Your refund of $' . number_format($this->refundAmount, 2) . ' has been processed and will appear on your original payment method within 5-10 business days.',
            'paypal' => 'Your refund of $' . number_format($this->refundAmount, 2) . ' has been processed and will be credited to your PayPal account within 3-5 business days.',
        ];

        return $refundInfo[$paymentMethod] ?? 'Your refund of $' . number_format($this->refundAmount, 2) . ' has been processed and will be credited within 5-10 business days.';
    }
} 