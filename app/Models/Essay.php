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

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }


    public function paperCode()
    {
        return $this->belongsTo(PaperCode::class);
    }

    // ModelEssay.php or PastPaper.php

    // Shared scope for both models
    public function scopeFilter($query, $filters)
    {
       
       
        return $query
            ->when(!empty($filters['years']), fn($q) => $q->whereIn('year', $filters['years']))
            ->when(!empty($filters['months']), fn($q) => $q->whereIn('month', $filters['months']))
            ->when(!empty($filters['marks']), fn($q) => $q->whereIn('marks', $filters['marks']))
            ->when(!empty($filters['topic']), fn($q) => $q->where('topic_id', $filters['topic']))
            ->when(!empty($filters['paper_code']), fn($q) => $q->where('paper_code_id', $filters['paper_code']))
            ->when(!empty($filters['qualification']), fn($q) => $q->where('qualiification_id', $filters['qualification']))
            ->when(!empty($filters['subject']), fn($q) => $q->where('subject_id', $filters['subject']))
            ->when(!empty($filters['exam_board']), fn($q) => $q->where('examboard_id', $filters['exam_board']))
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $term = $filters['search'];
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', "%$term%")
                        ->orWhere('description', 'like', "%$term%");
                });
            });
    }
}
