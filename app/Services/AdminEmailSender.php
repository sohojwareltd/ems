<?php

namespace App\Services;

use App\Mail\AdminCustomEmail;
use App\Models\AdminEmail;
use App\Models\EmailLog;
use App\Models\EmailRecipient;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class AdminEmailSender
{
    /**
     * Send email and log all recipients for reply tracking.
     *
     * @param AdminEmail $adminEmail
     * @return EmailLog|null
     * @throws InvalidArgumentException
     */
    public function send(AdminEmail $adminEmail): ?EmailLog
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

        // Validate all email addresses
        $invalidEmails = collect($allRecipients)
            ->reject(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->values()
            ->all();

        if ($invalidEmails !== []) {
            throw new InvalidArgumentException('Invalid email address found: ' . implode(', ', $invalidEmails));
        }

        // Create email log entry for tracking replies before sending.
        $emailLog = $this->createEmailLog($adminEmail, $to, $cc, $bcc);

        $replyToAddress = config('mail.reply_to.address');
        $replyToName = config('mail.reply_to.name');

        $successCount = 0;
        $failedRecipients = [];
        $failureReasons = [];

        foreach ($allRecipients as $recipient) {
            try {
                $mailable = new AdminCustomEmail($adminEmail);

                if (filled($replyToAddress)) {
                    $mailable->replyTo($replyToAddress, $replyToName);
                }

                // Send strictly one-by-one to avoid a single bulk delivery.
                Mail::to($recipient)->send($mailable);
                $successCount++;
            } catch (\Throwable $exception) {
                $failedRecipients[] = $recipient;
                $failureReasons[] = sprintf('%s: %s', $recipient, $exception->getMessage());
            }
        }

        $status = match (true) {
            $successCount === count($allRecipients) => 'sent',
            $successCount > 0 => 'partial',
            default => 'failed',
        };

        $emailLog->update([
            'status' => $status,
            'sent_at' => $successCount > 0 ? now() : null,
            'error_message' => $failedRecipients !== []
                ? 'Failed recipients: ' . implode(', ', $failedRecipients) . ' | Reasons: ' . implode(' ; ', $failureReasons)
                : null,
        ]);

        return $emailLog;
    }

    /**
     * Create an email log with all recipients.
     *
     * @param AdminEmail $adminEmail
     * @param array $to
     * @param array $cc
     * @param array $bcc
     * @return EmailLog
     */
    private function createEmailLog(AdminEmail $adminEmail, array $to, array $cc, array $bcc): EmailLog
    {
        // Create the main email log
        $emailLog = EmailLog::create([
            'admin_email_id' => $adminEmail->id,
            'subject' => $adminEmail->subject,
            'body' => $adminEmail->body,
            'from_email' => config('mail.from.address'),
            'total_recipients' => count($to) + count($cc) + count($bcc),
            'replied_count' => 0,
            'pending_count' => count($to) + count($cc) + count($bcc),
            'status' => 'queued',
        ]);

        // Create recipient records for each recipient type
        $recipients = [];

        // Add "to" recipients
        foreach ($to as $email) {
            $recipients[] = [
                'email_log_id' => $emailLog->id,
                'email' => strtolower(trim($email)),
                'type' => 'to',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Add "cc" recipients
        foreach ($cc as $email) {
            $recipients[] = [
                'email_log_id' => $emailLog->id,
                'email' => strtolower(trim($email)),
                'type' => 'cc',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Add "bcc" recipients
        foreach ($bcc as $email) {
            $recipients[] = [
                'email_log_id' => $emailLog->id,
                'email' => strtolower(trim($email)),
                'type' => 'bcc',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert recipients
        if (!empty($recipients)) {
            EmailRecipient::insert($recipients);
        }

        return $emailLog;
    }
}
