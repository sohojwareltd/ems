<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Checkout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'checkout';
    }
} 