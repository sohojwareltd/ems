<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = ['user_id', 'device_name', 'device_agent', 'session_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
}
}
