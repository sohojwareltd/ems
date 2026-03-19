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
        $to = AdminEmail::parseRecipients($adminEmail->to_emails);

        if ($to === []) {
            throw new InvalidArgumentException('At least one recipient email is required.');
        }

        $invalidEmails = collect($to)
            ->reject(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->values()
            ->all();

        if ($invalidEmails !== []) {
            throw new InvalidArgumentException('Invalid email address found: ' . implode(', ', $invalidEmails));
        }

        foreach ($to as $recipient) {
            Mail::to($recipient)->send(new AdminCustomEmail($adminEmail));
        }
    }
}
