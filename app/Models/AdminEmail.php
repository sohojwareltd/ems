<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminEmail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'email_groups' => 'array',
        'attachments' => 'array',
        'attachment_file_names' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getToRecipientsAttribute(): array
    {
        $recipients = [];

        // Add emails from selected email groups
        if (! empty($this->email_groups)) {
            $groupEmails = EmailGroup::whereIn('id', (array) $this->email_groups)
                ->whereNull('parent_id')
                ->with('children')
                ->get()
                ->map(function ($group) {
                    $emails = [$group->email];
                    foreach ($group->children as $child) {
                        if ($child->email) {
                            $emails[] = $child->email;
                        }
                    }
                    return array_filter($emails);
                })
                ->flatten()
                ->all();

            $recipients = array_merge($recipients, $groupEmails);
        }

        // Add custom emails
        $customEmails = self::parseRecipients($this->to_emails);
        $recipients = array_merge($recipients, $customEmails);

        return array_values(array_unique(array_filter($recipients)));
    }

    public function getCcRecipientsAttribute(): array
    {
        return self::parseRecipients($this->cc_emails);
    }

    public function getBccRecipientsAttribute(): array
    {
        return self::parseRecipients($this->bcc_emails);
    }

    public static function parseRecipients(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/[\s,;]+/', (string) $value) ?: [])
            ->map(fn (string $email): string => strtolower(trim($email)))
            ->filter(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }
}
