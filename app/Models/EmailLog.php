<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin email that this log belongs to.
     */
    public function adminEmail(): BelongsTo
    {
        return $this->belongsTo(AdminEmail::class, 'admin_email_id');
    }

    /**
     * Get all recipients for this email log.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class, 'email_log_id');
    }

    /**
     * Get recipients who replied.
     */
    public function repliedRecipients(): HasMany
    {
        return $this->recipients()->whereNotNull('replied_at');
    }

    /**
     * Get recipients who have not replied.
     */
    public function pendingRecipients(): HasMany
    {
        return $this->recipients()->whereNull('replied_at');
    }

    /**
     * Get count of replied recipients.
     */
    public function getRepliedCountAttribute(): int
    {
        return $this->repliedRecipients()->count();
    }

    /**
     * Get count of pending recipients.
     */
    public function getPendingCountAttribute(): int
    {
        return $this->pendingRecipients()->count();
    }

    /**
     * Update reply status for a specific email.
     *
     * @param string $email
     * @return bool
     */
    public function markReplyReceived(string $email): bool
    {
        $recipient = $this->recipients()
            ->where('email', strtolower(trim($email)))
            ->first();

        if ($recipient && !$recipient->replied_at) {
            $recipient->update(['replied_at' => now()]);
            
            // Update the email log counts
            $this->update([
                'replied_count' => $this->repliedRecipients()->count(),
                'pending_count' => $this->pendingRecipients()->count(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Get reply status summary.
     */
    public function getReplyStatusSummary(): array
    {
        return [
            'total' => $this->total_recipients,
            'replied' => $this->replied_count,
            'pending' => $this->pending_count,
            'replied_percentage' => $this->total_recipients > 0 
                ? round(($this->replied_count / $this->total_recipients) * 100, 2)
                : 0,
        ];
    }

    /**
     * Check if all recipients have replied.
     */
    public function allReplied(): bool
    {
        return $this->pending_count === 0;
    }

    /**
     * Get all individual email addresses from recipients.
     *
     * @return array
     */
    public function getEmails(): array
    {
        return $this->recipients()
            ->pluck('email')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get email addresses by recipient type.
     *
     * @param string $type to|cc|bcc
     * @return array
     */
    public function getEmailsByType(string $type): array
    {
        return $this->recipients()
            ->where('type', $type)
            ->pluck('email')
            ->values()
            ->all();
    }

    /**
     * Get all "to" recipient emails.
     *
     * @return array
     */
    public function toEmails(): array
    {
        return $this->getEmailsByType('to');
    }

    /**
     * Get all "cc" recipient emails.
     *
     * @return array
     */
    public function ccEmails(): array
    {
        return $this->getEmailsByType('cc');
    }

    /**
     * Get all "bcc" recipient emails.
     *
     * @return array
     */
    public function bccEmails(): array
    {
        return $this->getEmailsByType('bcc');
    }

    /**
     * Get emails that have replied (replied_at is not null).
     *
     * @return array
     */
    public function getRepliedEmails(): array
    {
        return $this->repliedRecipients()
            ->pluck('email')
            ->values()
            ->all();
    }

    /**
     * Get emails that have NOT replied (replied_at is null).
     *
     * @return array
     */
    public function getPendingEmails(): array
    {
        return $this->pendingRecipients()
            ->pluck('email')
            ->values()
            ->all();
    }

    /**
     * Get replied recipients with full details including replied_at timestamp.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRepliedRecipientsWithDetails()
    {
        return $this->repliedRecipients()
            ->get()
            ->map(fn ($recipient) => [
                'email' => $recipient->email,
                'type' => $recipient->type,
                'replied_at' => $recipient->replied_at,
            ]);
    }

    /**
     * Get pending recipients with full details.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingRecipientsWithDetails()
    {
        return $this->pendingRecipients()
            ->get()
            ->map(fn ($recipient) => [
                'email' => $recipient->email,
                'type' => $recipient->type,
                'replied_at' => $recipient->replied_at,
            ]);
    }
}
