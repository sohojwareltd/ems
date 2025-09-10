<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case REQUIRES_PAYMENT_METHOD = 'requires_payment_method';
    case REQUIRES_CONFIRMATION = 'requires_confirmation';
    case REQUIRES_ACTION = 'requires_action';
    case PROCESSING = 'processing';
    case REQUIRES_CAPTURE = 'requires_capture';
    case CANCELED = 'canceled';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';

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
            self::REQUIRES_PAYMENT_METHOD => 'Requires Payment Method',
            self::REQUIRES_CONFIRMATION => 'Requires Confirmation',
            self::REQUIRES_ACTION => 'Requires Action',
            self::PROCESSING => 'Processing',
            self::REQUIRES_CAPTURE => 'Requires Capture',
            self::CANCELED => 'Canceled',
            self::SUCCEEDED => 'Succeeded',
            self::FAILED => 'Failed',
        };
    }

    /**
     * Get status color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::SUCCEEDED => 'success',
            self::PROCESSING => 'info',
            self::REQUIRES_PAYMENT_METHOD, self::REQUIRES_CONFIRMATION, self::REQUIRES_ACTION, self::REQUIRES_CAPTURE => 'warning',
            self::FAILED, self::CANCELED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if status is successful
     */
    public function isSuccessful(): bool
    {
        return $this === self::SUCCEEDED;
    }

    /**
     * Check if status is failed
     */
    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }

    /**
     * Check if status is pending
     */
    public function isPending(): bool
    {
        return in_array($this, [
            self::REQUIRES_PAYMENT_METHOD,
            self::REQUIRES_CONFIRMATION,
            self::REQUIRES_ACTION,
            self::PROCESSING,
            self::REQUIRES_CAPTURE,
        ]);
    }

    /**
     * Check if status is canceled
     */
    public function isCanceled(): bool
    {
        return $this === self::CANCELED;
    }

    /**
     * Get pending status cases
     */
    public static function pendingCases(): array
    {
        return [
            self::REQUIRES_PAYMENT_METHOD,
            self::REQUIRES_CONFIRMATION,
            self::REQUIRES_ACTION,
            self::PROCESSING,
            self::REQUIRES_CAPTURE,
        ];
    }
}
