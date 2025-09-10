<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => PaymentStatus::class,
        'type' => PaymentType::class,
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the subscription that owns the payment
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::SUCCEEDED;
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED || $this->failed_at !== null;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return in_array($this->status, PaymentStatus::pendingCases());
    }

    /**
     * Get formatted amount for display
     */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount, 2) . ' ' . strtoupper($this->currency),
        );
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', PaymentStatus::SUCCEEDED);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', PaymentStatus::FAILED)
                    ->orWhereNotNull('failed_at');
    }

    /**
     * Scope for subscription payments
     */
    public function scopeSubscription($query)
    {
        return $query->where('type', PaymentType::SUBSCRIPTION);
    }

    /**
     * Scope for one-time payments
     */
    public function scopeOneTime($query)
    {
        return $query->where('type', PaymentType::ONE_TIME);
    }
}
