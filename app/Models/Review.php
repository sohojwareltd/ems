<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'heading',
        'title',
        'content',
        'rating',
        'avatar',
        'country_code',
        'is_featured',
        'is_active',
        'is_approved',
        'approved_at',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active reviews
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for featured reviews
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordered reviews
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // If avatar is a full URL, return it directly
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            
            // If avatar is a local path, return the storage URL
            return asset('storage/' . $this->avatar);
        }
        
        // Return a placeholder avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=9B8B7A&background=FAF9F7';
    }

    /**
     * Get stars HTML
     */
    public function getStarsHtmlAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="bi bi-star-fill text-warning"></i>';
            } else {
                $stars .= '<i class="bi bi-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get country flag URL
     */
    public function getCountryFlagUrlAttribute(): ?string
    {
        if ($this->country_code) {
            return 'https://flagcdn.com/w40/' . strtolower($this->country_code) . '.png';
        }
        return null;
    }
}
