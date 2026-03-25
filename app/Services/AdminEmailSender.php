<?php

namespace App\Services;

use App\Mail\AdminCustomEmail;
use App\Models\AdminEmail;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class AdminEmailSender
{
    public function send(AdminEmail $adminEmail): void
    {
        $to = $adminEmail->to_recipients;
        $cc = $adminEmail->cc_recipients;
        $bcc = $adminEmail->bcc_recipients;
        $allRecipients = collect([$to, $cc, $bcc])
            ->flatten()
            ->filter()
            ->values()
            ->all();

        if ($allRecipients === []) {
            throw new InvalidArgumentException('At least one recipient email is required.');
        }

        $invalidEmails = collect($allRecipients)
            ->reject(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->values()
            ->all();

        if ($invalidEmails !== []) {
            throw new InvalidArgumentException('Invalid email address found: ' . implode(', ', $invalidEmails));
        }

        Mail::queue(
            (new AdminCustomEmail($adminEmail))
                ->to($to)
                ->cc($cc)
                ->bcc($bcc)
        );
    }
}
