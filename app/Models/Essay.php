<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    protected $guarded = [];



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
}
