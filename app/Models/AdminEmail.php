<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminEmail extends Model
{
    protected $guarded = [];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getToRecipientsAttribute(): array
    {
        return self::parseRecipients($this->to_emails);
    }

    public static function parseRecipients(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/[\s,;]+/', (string) $value) ?: [])
            ->map(fn (string $email): string => trim($email))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
