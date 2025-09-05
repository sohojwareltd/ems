<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AudioBook;
use App\Models\Traits\SelfHealingSlug;

class Product extends Model
{
    use SelfHealingSlug;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'brand_id',
        'thumbnail',
        'gallery',
        'price',
        'compare_at_price',
        'cost_per_item',
        'track_quantity',
        'sku',
        'barcode',
        'weight',
        'height',
        'width',
        'length',
        'stock',
        'status',
        'published_at',
        'tags',
        'options',
        'has_variants',
        'variants',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_digital',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'options' => 'array',
        'variants' => 'array',
        'published_at' => 'datetime',
        'has_variants' => 'boolean',
        'is_digital' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class, 'audio_book_product');
    }

    public function hasVariants(): bool
    {
        return $this->has_variants;
    }

    /**
     * Check if product is digital
     */
    public function isDigital(): bool
    {
        return (bool) $this->is_digital;
    }

    public function getStock(): int
    {
        return $this->hasVariants() ? array_sum(array_column($this->variants, 'stock')) : $this->stock;
    }

    /**
     * Get minimum price from variants
     */
    public function getMinPrice(): float
    {
        if (!$this->hasVariants() || empty($this->variants)) {
            return $this->price;
        }

        $prices = collect($this->variants)->pluck('price')->filter();
        return $prices->isNotEmpty() ? $prices->min() : $this->price;
    }

    /**
     * Get maximum price from variants
     */
    public function getMaxPrice(): float
    {
        if (!$this->hasVariants() || empty($this->variants)) {
            return $this->price;
        }

        $prices = collect($this->variants)->pluck('price')->filter();
        return $prices->isNotEmpty() ? $prices->max() : $this->price;
    }

    /**
     * Get price range as string
     */
    public function getPriceRange(): string
    {
        if (!$this->hasVariants()) {
            return '$' . number_format($this->price, 2);
        }

        $minPrice = $this->getMinPrice();
        $maxPrice = $this->getMaxPrice();

        if ($minPrice == $maxPrice) {
            return '$' . number_format($minPrice, 2);
        }

        return '$' . number_format($minPrice, 2) . ' - $' . number_format($maxPrice, 2);
    }

    /**
     * Get image URL for frontend display
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->thumbnail) {
            // If thumbnail is a full URL, return it directly
            if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
                return $this->thumbnail;
            }
            
            // If thumbnail is a local path, return the storage URL
            return asset('storage/' . $this->thumbnail);
        }
        
        // Return a placeholder image if no thumbnail
        return 'https://via.placeholder.com/300x200?text=No+Image';
    }

    /**
     * Get gallery images for frontend display
     */
    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return asset('storage/' . $image);
        }, $this->gallery);
    }
}
