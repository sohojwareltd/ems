<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    protected function ammount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value * 100,
            set: fn ($value) => $value / 100,
        );
    }
}
