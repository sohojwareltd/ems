<?php

namespace App\Mail;

use App\Models\AdminEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AdminEmail $adminEmail)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->adminEmail->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.custom-message',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
