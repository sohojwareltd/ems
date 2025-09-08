<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    protected $guarded = [];

      protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'options' => 'array',
        'published_at' => 'datetime',
    ];



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
