<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShippingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order Has Been Shipped - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.shipping-confirmation',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->billing_address['first_name'] . ' ' . $this->order->billing_address['last_name'],
                'orderNumber' => $this->order->order_number,
                'trackingNumber' => $this->order->shipping_tracking,
                'shippingMethod' => $this->order->shipping_method,
                'shippingAddress' => $this->order->shipping_address,
                'estimatedDelivery' => $this->getEstimatedDelivery(),
                'trackingUrl' => $this->getTrackingUrl(),
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
     * Get estimated delivery date based on shipping method
     */
    private function getEstimatedDelivery(): string
    {
        $deliveryTimes = [
            'standard' => '3-5 business days',
            'express' => '1-2 business days',
            'overnight' => 'Next business day',
            'free' => '5-7 business days',
        ];

        return $deliveryTimes[$this->order->shipping_method] ?? '3-5 business days';
    }

    /**
     * Get tracking URL based on tracking number format
     */
    private function getTrackingUrl(): string
    {
        $trackingNumber = $this->order->shipping_tracking;
        
        if (!$trackingNumber) {
            return '';
        }

        // UPS tracking (starts with 1Z)
        if (preg_match('/^1Z/', $trackingNumber)) {
            return "https://www.ups.com/track?tracknum={$trackingNumber}";
        }

        // FedEx tracking (starts with 7 or 8 digits)
        if (preg_match('/^[78]\d{7,}$/', $trackingNumber)) {
            return "https://www.fedex.com/fedextrack/?trknbr={$trackingNumber}";
        }

        // USPS tracking (starts with 9 or 9 digits)
        if (preg_match('/^9\d{15,}$/', $trackingNumber)) {
            return "https://tools.usps.com/go/TrackConfirmAction?tLabels={$trackingNumber}";
        }

        // DHL tracking (starts with 3 or 4 digits)
        if (preg_match('/^[34]\d{8,}$/', $trackingNumber)) {
            return "https://www.dhl.com/en/express/tracking.html?AWB={$trackingNumber}";
        }

        // Default to a generic tracking service
        return "https://www.trackingmore.com/track/{$trackingNumber}";
    }
} 