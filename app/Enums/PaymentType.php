<?php

namespace App\Enums;

enum PaymentType: string
{
    case SUBSCRIPTION = 'subscription';
    case ONE_TIME = 'one_time';

    /**
     * Get all type values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get type labels for display
     */
    public function label(): string
    {
        return match ($this) {
            self::SUBSCRIPTION => 'Subscription',
            self::ONE_TIME => 'One Time',
        };
    }

    /**
     * Get type color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::SUBSCRIPTION => 'primary',
            self::ONE_TIME => 'secondary',
        };
    }

    /**
     * Check if type is subscription
     */
    public function isSubscription(): bool
    {
        return $this === self::SUBSCRIPTION;
    }

    /**
     * Check if type is one-time
     */
    public function isOneTime(): bool
    {
        return $this === self::ONE_TIME;
    }
}
