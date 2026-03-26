<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailRecipient extends Model
{
    protected $table = 'email_recipients';

    protected $guarded = [];

    protected $casts = [
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the email log this recipient belongs to.
     */
    public function emailLog(): BelongsTo
    {
        return $this->belongsTo(EmailLog::class, 'email_log_id');
    }

    /**
     * Check if this recipient has replied.
     */
    public function hasReplied(): bool
    {
        return $this->replied_at !== null;
    }

    /**
     * Scope query to only include replied recipients.
     */
    public function scopeReplied($query)
    {
        return $query->whereNotNull('replied_at');
    }

    /**
     * Scope query to only include pending recipients.
     */
    public function scopePending($query)
    {
        return $query->whereNull('replied_at');
    }

    /**
     * Scope query by recipient type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get unique emails from query.
     */
    public function scopeUnique($query)
    {
        return $query->distinct('email');
    }

    /**
     * Get emails as simple array.
     */
    public static function getEmailsByLogId(int $logId, ?string $type = null): array
    {
        $query = static::where('email_log_id', $logId);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->pluck('email')->unique()->values()->all();
    }

    /**
     * Get all replied emails (where replied_at is not null).
     *
     * @return array
     */
    public static function getRepliedEmails(): array
    {
        return static::replied()
            ->pluck('email')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get replied emails by email log ID.
     *
     * @param int $logId
     * @return array
     */
    public static function getRepliedEmailsByLogId(int $logId): array
    {
        return static::where('email_log_id', $logId)
            ->replied()
            ->pluck('email')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get pending emails by email log ID.
     *
     * @param int $logId
     * @return array
     */
    public static function getPendingEmailsByLogId(int $logId): array
    {
        return static::where('email_log_id', $logId)
            ->pending()
            ->pluck('email')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get replied recipients with email log ID.
     *
     * @param int $logId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRepliedRecipientsWithDetailsByLogId(int $logId)
    {
        return static::where('email_log_id', $logId)
            ->replied()
            ->get()
            ->map(fn ($recipient) => [
                'email' => $recipient->email,
                'type' => $recipient->type,
                'replied_at' => $recipient->replied_at?->format('Y-m-d H:i:s'),
            ]);
    }
}
