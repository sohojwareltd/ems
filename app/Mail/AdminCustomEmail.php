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
use Illuminate\Support\Facades\Storage;

class AdminCustomEmail extends Mailable
{
    use SerializesModels;

    public function __construct(public AdminEmail $adminEmail) {}

    public function envelope(): Envelope
    {
   
        return new Envelope(
            subject: $this->adminEmail->subject,
            replyTo: [new Address('reply@inbound.economicsmadesimple.com',  setting('store.name'))]
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
            ->filter(fn (string $path): bool => Storage::disk('local')->exists($path))
            ->map(function (string $path, int | string $key): Attachment {
                $fileName = $this->resolveAttachmentFileName($path, $key);

                return Attachment::fromStorageDisk('local', $path)
                    ->as($fileName);
            })
            ->values()
            ->all();
    }

    private function resolveAttachmentFileName(string $path, int | string $key): string
    {
        $fileNames = (array) ($this->adminEmail->attachment_file_names ?? []);

        $nameByKey = data_get($fileNames, (string) $key);

        if (is_string($nameByKey) && $nameByKey !== '') {
            return $nameByKey;
        }

        $nameByPath = data_get($fileNames, $path);

        if (is_string($nameByPath) && $nameByPath !== '') {
            return $nameByPath;
        }

        $exactMatch = collect($fileNames)
            ->filter(fn (mixed $name): bool => is_string($name) && $name !== '')
            ->first(fn (string $name): bool => $name === basename($path));

        return $exactMatch ?: basename($path);
    }
}
