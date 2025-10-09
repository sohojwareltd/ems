<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded=[];

    public function paperCode()
    {
        return $this->belongsTo(PaperCode::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function essays()
{
    return $this->belongsToMany(Essay::class, 'essay_topic', 'topic_id', 'essay_id')
        ->withTimestamps();
}

}