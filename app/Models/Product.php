<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SelfHealingSlug;

class Product extends Model
{
    // use SelfHealingSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'gallery',
        'price',
        'compare_at_price',
        'cost_per_item',
        'status',
        'published_at',
        'tags',
        'options',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_digital',
        'pdf_file',
        'ppt_file',
        'zip_file',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'options' => 'array',
        'published_at' => 'datetime',
        'is_digital' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Check if product is digital
     */
    public function isDigital(): bool
    {
        return (bool) $this->is_digital;
    }

    /**
     * Get image URL for frontend display
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
                return $this->thumbnail;
            }
            return asset('storage/' . $this->thumbnail);
        }

        return 'https://via.placeholder.com/300x200?text=No+Image';
    }


    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, );
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_type_id');
    }

    public function qualiification()
    {
        return $this->belongsTo(Qualification::class, 'qualiification_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function examboard()
    {
        return $this->belongsTo(Examboard::class, 'examboard_id');
    }

    /**
     * Get all reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get approved reviews for this product
     */
    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->approved()->active();
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get gallery images for frontend display
     */
    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return asset('storage/' . $image);
        }, $this->gallery);
    }
}

// Height,Length,Width,Weight,track_quantity,stock,barcode,sku,has_variants