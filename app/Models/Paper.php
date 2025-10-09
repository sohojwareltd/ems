<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $guarded = [];

    public function paperCode()
    {
        return $this->belongsTo(PaperCode::class);
    }

    public function pastPapers()
    {
        return $this->hasMany(PastPaper::class, 'paper_id');
    }
    public function essays()
    {
        return $this->hasMany(Essay::class, 'paper_id');
    }
}