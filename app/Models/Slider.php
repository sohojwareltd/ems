<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_url',
        'button_color',
        'position',
        'is_active',
        'sort_order',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Scope to get only active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', Carbon::now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', Carbon::now());
                    });
    }

    /**
     * Scope to get sliders by position
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        return 'https://via.placeholder.com/1200x400?text=No+Image';
    }

    /**
     * Check if slider is currently active
     */
    public function getIsCurrentlyActiveAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute()
    {
        if (!$this->starts_at && !$this->ends_at) {
            return 'Always active';
        }

        if ($this->starts_at && $this->ends_at) {
            return $this->starts_at->format('M j, Y') . ' - ' . $this->ends_at->format('M j, Y');
        }

        if ($this->starts_at) {
            return 'From ' . $this->starts_at->format('M j, Y');
        }

        if ($this->ends_at) {
            return 'Until ' . $this->ends_at->format('M j, Y');
        }

        return 'Always active';
    }
}
