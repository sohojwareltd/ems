<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
        'is_hide' => 'boolean',
        'features' => 'array',
        'trial_period_days' => 'integer',
        'interval_count' => 'integer',
        'is_coupon_enabled' => 'boolean',
        'coupon_max_uses' => 'integer',
        'coupon_total_used' => 'integer',
    ];

    /**
     * Get the price attribute in cents for Stripe
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100, // Convert from cents to dollars for display
            set: fn ($value) => $value * 100, // Convert from dollars to cents for storage
        );
    }

    /**
     * Get the formatted price for display
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->price, 2),
        );
    }

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if plan is active
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Get the interval display name
     */
    public function getIntervalDisplayAttribute(): string
    {
        $interval = $this->interval;
        if ($this->interval_count > 1) {
            $interval = $this->interval_count . ' ' . Str::plural($this->interval);
        }

        return $interval;
    }

    public function hasSubscriptionCoupon(): bool
    {
        return $this->is_coupon_enabled && filled($this->coupon_code);
    }

    public function validateSubscriptionCoupon(?string $couponCode): array
    {
        $normalizedCoupon = Str::upper(trim((string) $couponCode));

        if (! $this->hasSubscriptionCoupon()) {
            return [
                'valid' => false,
                'message' => 'This plan does not have an active subscription coupon.',
            ];
        }

        if ($normalizedCoupon === '') {
            return [
                'valid' => false,
                'message' => 'Please enter a coupon code.',
            ];
        }

        if (Str::upper((string) $this->coupon_code) !== $normalizedCoupon) {
            return [
                'valid' => false,
                'message' => 'Invalid coupon code for this plan.',
            ];
        }

        if ($this->coupon_max_uses !== null && $this->coupon_total_used >= $this->coupon_max_uses) {
            return [
                'valid' => false,
                'message' => 'This coupon has reached its usage limit.',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully.',
            'code' => $normalizedCoupon,
        ];
    }

    public function calculateStandardEndDate(?Carbon $startsAt = null): Carbon
    {
        $startsAt ??= now();

        return match ($this->interval) {
            'month' => $startsAt->copy()->addMonths(max(1, $this->interval_count ?? 1)),
            'year' => $startsAt->copy()->addYears(max(1, $this->interval_count ?? 1)),
            'week' => $startsAt->copy()->addWeeks(max(1, $this->interval_count ?? 1)),
            'day' => $startsAt->copy()->addDays(max(1, $this->interval_count ?? 1)),
            default => $startsAt->copy(),
        };
    }
}
