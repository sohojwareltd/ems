<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Traits\SelfHealingSlug;

class BlogPost extends Model
{
    use HasFactory, SelfHealingSlug;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_featured',
        'allow_comments',
        'published_at',
        'view_count',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created this post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for this post
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Scope to get only featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get posts by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    /**
     * Scope to search posts
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
                return $this->featured_image;
            }
            return asset('storage/' . $this->featured_image);
        }
        return 'https://via.placeholder.com/800x400?text=No+Image';
    }

    /**
     * Get gallery images URLs
     */
    public function getGalleryUrlsAttribute()
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

    /**
     * Get reading time in minutes
     */
    public function getReadingTimeAttribute()
    {
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / $wordsPerMinute);
        return max(1, $readingTime);
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : 'Not published';
    }

    /**
     * Check if post is published
     */
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && $this->published_at && $this->published_at <= Carbon::now();
    }

    /**
     * Boot method to automatically generate slug and excerpt
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            if (empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 150);
            }

            if ($post->status === 'published' && !$post->published_at) {
                $post->published_at = Carbon::now();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            if ($post->isDirty('content') && empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 150);
            }

            if ($post->isDirty('status') && $post->status === 'published' && !$post->published_at) {
                $post->published_at = Carbon::now();
            }
        });
    }
}
