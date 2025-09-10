<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
        'features' => 'array',
        'trial_period_days' => 'integer',
        'interval_count' => 'integer',
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
            $interval = $this->interval_count . ' ' . \Illuminate\Support\Str::plural($this->interval);
        }
        return $interval;
    }
}
