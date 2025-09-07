<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    protected $guarded = [];



    public function category()
{
    return $this->belongsTo(\App\Models\Category::class, 'category_id');
}

public function brand()
{
    return $this->belongsTo(\App\Models\Brand::class, 'brand_id');
}
public function resource()
{
    return $this->belongsTo(\App\Models\Resource_type::class, 'resource_type_id');
}
public function qualiification()
{
    return $this->belongsTo(\App\Models\Qualification::class, 'qualiification_id');
}
public function subject()
{
    return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
}
public function examboard()
{
    return $this->belongsTo(\App\Models\Examboard::class, 'examboard_id');
}



}
