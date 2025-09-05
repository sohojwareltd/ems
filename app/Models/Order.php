<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'status', 'payment_method', 'payment_status', 'shipping_status', 'payment_intent_id', 'total', 'currency', 'shipping_address', 'billing_address', 'notes', 'shipping_method', 'tracking', 'coupon_id', 'coupon_code', 'discount', 'discount_amount', 'subtotal', 'tax_amount', 'shipping_amount',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    // Payment status constants
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';
    public const PAYMENT_CANCELLED = 'cancelled';

    // Order status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function discounts()
    {
        return $this->hasMany(OrderDiscount::class);
    }


    public function histories()
    {
        return $this->hasMany(\App\Models\OrderHistory::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->lines->sum(function ($line) {
            return $line->price * $line->quantity;
        });
    }

    public function getTotalDiscountAttribute()
    {
        return $this->discounts->sum('amount');
    }

    public function getFinalTotalAttribute()
    {
        return $this->subtotal - $this->total_discount;
    }

    public function recalculateTotal()
    {
        $this->total = $this->final_total;
        $this->save();
    }

    /**
     * Get the color class for the status badge.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'shipped' => 'primary',
            default => 'secondary',
        };
    }
} 