<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastPaper extends Model
{
    protected $guarded = [];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
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


    public function scopeFilter($query, $filters)
    {
        return $query
            ->when(!empty($filters['years']), fn($q) => $q->whereIn('year', $filters['years']))
            ->when(!empty($filters['months']), fn($q) => $q->whereIn('month', $filters['months']))
            ->when(!empty($filters['marks']), fn($q) => $q->whereIn('mark', $filters['marks']))
            ->when(!empty($filters['topic']), fn($q) => $q->where('topic_id', $filters['topic']))
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
