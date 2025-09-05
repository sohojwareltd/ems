<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
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
            subject: 'New Order Received - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-order',
            with: [
                'order' => $this->order,
                'orderNumber' => $this->order->order_number,
                'orderDate' => $this->order->created_at->format('F j, Y \a\t g:i A'),
                'customerName' => $this->order->billing_address['first_name'] . ' ' . $this->order->billing_address['last_name'],
                'customerEmail' => $this->order->billing_address['email'],
                'customerPhone' => $this->order->billing_address['phone'],
                'total' => number_format($this->order->total, 2),
                'itemCount' => $this->order->lines->count(),
                'paymentMethod' => ucfirst($this->order->payment_method),
                'shippingAddress' => $this->order->shipping_address,
                'billingAddress' => $this->order->billing_address,
                'items' => $this->order->lines,
                'adminUrl' => route('filament.admin.resources.orders.edit', $this->order),
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
} 