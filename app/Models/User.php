<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\SubscriptionStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * Cached active subscription to avoid multiple queries
     */
    protected $cachedActiveSubscription = null;
    protected $activeSubscriptionLoaded = false;


    public const ROLE_CUSTOMER = 2;
    public const ROLE_ADMIN = 1;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('admin.panel.access');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'stripe_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname;
    }
    public function firstName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->attributes['name'],
            set: fn($value) => $this->name = $value,
        );
    }

    public function lastName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->attributes['lastname'],
            set: fn($value) => $this->lastname = $value,
        );
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function defaultSubscription()
    {
        return $this->hasOne(Subscription::class)->where('type', 'default');
    }

    /**
     * Get the payments for the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        if (!$this->activeSubscriptionLoaded) {
            $this->cachedActiveSubscription = $this->subscriptions()
                ->where('status', SubscriptionStatus::ACTIVE)
                ->orWhere('status', SubscriptionStatus::TRIALING)
                ->latest()
                ->first();
            $this->activeSubscriptionLoaded = true;
        }

        return $this->cachedActiveSubscription;
    }

    /**
     * Clear the cached active subscription (useful when subscription status changes)
     */
    public function clearActiveSubscriptionCache(): void
    {
        $this->cachedActiveSubscription = null;
        $this->activeSubscriptionLoaded = false;
    }

    /**
     * Refresh the active subscription cache
     */
    public function refreshActiveSubscription()
    {
        $this->clearActiveSubscriptionCache();
        return $this->activeSubscription();
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    /**
     * Check if user is on trial.
     */
    public function isOnTrial(): bool
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->isOnTrial();
    }

    /**
     * Get the user's Stripe customer ID.
     */
    public function getStripeCustomerId(): ?string
    {
        return $this->stripe_customer_id;
    }

    /**
     * Get the role for the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user can perform an action (compatible with Laravel Gates).
     */
    public function can($ability, $arguments = []): bool
    {
        return $this->hasPermission($ability);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission($permissions): bool
    {
        return collect($permissions)->contains(function ($permission) {
            return $this->hasPermission($permission);
        });
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions($permissions): bool
    {
        return collect($permissions)->every(function ($permission) {
            return $this->hasPermission($permission);
        });
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->isAdmin();
    }

    /**
     * Get all permissions for the user.
     */
    public function getAllPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        if (!$this->role) {
            return collect();
        }

        return $this->role->permissions;
    }
}
