<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


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


    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class, 'audio_book_user')->withTimestamps()->withPivot('unlocked_at');
    }

    public function incrementAudioBookDownloadCount($audioBookId, $file)
    {
        $pivot = $this->audioBooks()->where('audio_book_id', $audioBookId)->first()?->pivot;
        if (!$pivot) return 0;
        $counts = $pivot->download_count ? json_decode($pivot->download_count, true) : [];
        $counts[$file] = ($counts[$file] ?? 0) + 1;
        $pivot->download_count = json_encode($counts);
        $pivot->save();
        return $counts[$file];
    }

    public function getAudioBookDownloadCount($audioBookId, $file)
    {
        $pivot = $this->audioBooks()->where('audio_book_id', $audioBookId)->first()?->pivot;
        if (!$pivot) return 0;
        $counts = $pivot->download_count ? json_decode($pivot->download_count, true) : [];
        return $counts[$file] ?? 0;
    }

    /**
     * Check if user has access to a specific audiobook
     * 
     * @param int $audioBookId
     * @return bool
     */
    public function hasAudioBookAccess($audioBookId): bool
    {
        return $this->audioBooks()->where('audio_book_id', $audioBookId)->exists();
    }

    /**
     * Get all audiobooks the user has access to
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAccessibleAudioBooks()
    {
        return $this->audioBooks()->with('products')->get();
    }
}
