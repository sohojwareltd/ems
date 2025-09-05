<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioBook extends Model
{
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'audio_files',
        'duration',
        'author',
        'download_limit',

    ];

    public function getCoverImageUrl()
    {
        return asset('storage/' . $this->cover_image);
    }

    protected $casts = [
        'audio_files' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'audio_book_product');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'audio_book_user')->withTimestamps()->withPivot('unlocked_at');
    }
}
