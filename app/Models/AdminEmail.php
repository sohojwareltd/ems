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

    public function setAttachmentFileNamesAttribute(mixed $value): void
    {
        $attachments = $this->normalizeAttachments($this->attributes['attachments'] ?? $this->attachments ?? []);
        $fileNames = $this->normalizeAttachmentFileNames($value, $attachments);

        $this->attributes['attachment_file_names'] = json_encode($fileNames);
    }

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

    private function normalizeAttachmentFileNames(mixed $value, array $attachments): array
    {
        if (! is_array($value) || $value === []) {
            return [];
        }

        if (array_is_list($value)) {
            return collect($value)
                ->filter(fn (mixed $name): bool => is_string($name) && trim($name) !== '')
                ->map(fn (string $name): string => trim($name))
                ->values()
                ->all();
        }

        if ($attachments !== []) {
            return collect($attachments)
                ->map(function (string $path) use ($value): string {
                    $name = data_get($value, $path);

                    if (is_string($name) && trim($name) !== '') {
                        return trim($name);
                    }

                    return basename($path);
                })
                ->values()
                ->all();
        }

        return collect($value)
            ->filter(fn (mixed $name): bool => is_string($name) && trim($name) !== '')
            ->map(fn (string $name): string => trim($name))
            ->values()
            ->all();
    }

    private function normalizeAttachments(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn (mixed $path): bool => is_string($path) && trim($path) !== ''));
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return array_values(array_filter($decoded, fn (mixed $path): bool => is_string($path) && trim($path) !== ''));
            }
        }

        return [];
    }
}
