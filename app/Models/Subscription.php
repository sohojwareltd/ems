<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Subscription extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'metadata' => 'array',
        'quantity' => 'integer',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for the subscription
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get payments for this subscription
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::ACTIVE;
    }

    /**
     * Check if the subscription is on trial
     */
    public function isOnTrial(): bool
    {
        return $this->status === SubscriptionStatus::TRIALING &&
            $this->trial_ends_at &&
            $this->trial_ends_at->isFuture();
    }

    public function hasTrialEnded(): bool
    {
        return $this->status === SubscriptionStatus::TRIALING &&
            $this->trial_ends_at &&
            $this->trial_ends_at->isPast();
    }

    /**
     * Check if the subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === SubscriptionStatus::CANCELED;
    }

    /**
     * Check if the subscription is past due
     */
    public function isPastDue(): bool
    {
        return $this->status === SubscriptionStatus::PAST_DUE;
    }

    /**
     * Check if the subscription has ended
     */
    public function hasEnded(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    /**
     * Get the trial days remaining
     */
    public function trialDaysRemaining(): int
    {
        if (!$this->isOnTrial()) {
            return 0;
        }

        return $this->trial_ends_at->diffInDays(now());
    }

    /**
     * Check if subscription is valid (active or trialing)
     */
    public function isValid(): bool
    {
        return $this->isActive() || $this->isOnTrial();
    }


    public function daysUntilEnd(): ?int
    {
        return $this->ends_at ? now()->diffInDays($this->ends_at, false) : null;
    }

    public function isExpiringSoon(int $days = 3): bool
    {
        return $this->ends_at && $this->ends_at->isFuture() &&
            now()->diffInDays($this->ends_at) <= $days;
    }

    // --- Default Helpers ---

    public function isDefault(): bool
    {
        return $this->type === 'default';
    }
}
