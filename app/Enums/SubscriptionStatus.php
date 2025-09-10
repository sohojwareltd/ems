<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case INCOMPLETE = 'incomplete';
    case INCOMPLETE_EXPIRED = 'incomplete_expired';
    case TRIALING = 'trialing';
    case ACTIVE = 'active';
    case PAST_DUE = 'past_due';
    case CANCELED = 'canceled';
    case UNPAID = 'unpaid';
    case PAUSED = 'paused';

    /**
     * Get all status values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status labels for display
     */
    public function label(): string
    {
        return match ($this) {
            self::INCOMPLETE => 'Incomplete',
            self::INCOMPLETE_EXPIRED => 'Incomplete Expired',
            self::TRIALING => 'Trialing',
            self::ACTIVE => 'Active',
            self::PAST_DUE => 'Past Due',
            self::CANCELED => 'Canceled',
            self::UNPAID => 'Unpaid',
            self::PAUSED => 'Paused',
        };
    }

    /**
     * Get status color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::TRIALING => 'info',
            self::PAST_DUE => 'warning',
            self::CANCELED, self::UNPAID => 'danger',
            self::PAUSED => 'secondary',
            default => 'primary',
        };
    }

    /**
     * Check if status is active
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if status is trialing
     */
    public function isTrialing(): bool
    {
        return $this === self::TRIALING;
    }

    /**
     * Check if status is canceled
     */
    public function isCanceled(): bool
    {
        return $this === self::CANCELED;
    }

    /**
     * Check if status is past due
     */
    public function isPastDue(): bool
    {
        return $this === self::PAST_DUE;
    }

    /**
     * Check if status is valid (active or trialing)
     */
    public function isValid(): bool
    {
        return $this->isActive() || $this->isTrialing();
    }
}
