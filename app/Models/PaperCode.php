<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperCode extends Model
{
    protected $guarded = [];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
