<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyPasswordChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $verifyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token, $verifyUrl)
    {
        $this->user = $user;
        $this->token = $token;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Password Change Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.verify-password-change',
            with: [
                'user' => $this->user,
                'token' => $this->token,
                'verifyUrl' => $this->verifyUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
