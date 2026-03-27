<?php

namespace App\Mail;

use App\Models\AdminEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AdminCustomEmail extends Mailable
{
    use SerializesModels;

    public function __construct(public AdminEmail $adminEmail) {}

    public function envelope(): Envelope
    {
   
        return new Envelope(
            subject: $this->adminEmail->subject,
            replyTo: [new Address('reply@inbound.economicsmadesimple.com', 'Taylor Otwell')]
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
        return collect($this->adminEmail->attachments ?? [])
            ->map(function (string $path, int | string $key): Attachment {
                $fileName = data_get($this->adminEmail->attachment_file_names, $key, basename($path));

                return Attachment::fromStorageDisk('local', $path)
                    ->as($fileName);
            })
            ->values()
            ->all();
    }
}
