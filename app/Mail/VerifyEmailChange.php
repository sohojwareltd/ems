<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newEmail;
    public $token;
    public $verifyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $newEmail, $token, $verifyUrl)
    {
        $this->user = $user;
        $this->newEmail = $newEmail;
        $this->token = $token;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email Change Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.verify-email-change',
            with: [
                'user' => $this->user,
                'new_email' => $this->newEmail,
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
