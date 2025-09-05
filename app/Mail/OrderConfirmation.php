<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
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
            subject: 'Order Confirmation - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $billingAddress = is_array($this->order->billing_address) ? $this->order->billing_address : [];

        
        $firstName    = $billingAddress['first_name'] ;
        $lastName     = $billingAddress['last_name'] ?? '';
        $customerName = trim("{$firstName} {$lastName}");
        
        return new Content(
            view: 'emails.orders.confirmation',
            with: [
                'order'        => $this->order,
                'customerName' => $customerName,
                'orderNumber'  => $this->order->id,
                'orderDate'    => $this->order->created_at->format('F j, Y'),
                'total'        => number_format($this->order->total, 2),
                'items'        => $this->order->lines,
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
