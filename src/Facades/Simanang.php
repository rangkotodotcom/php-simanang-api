<?php

namespace Rangkotodotcom\Simanang\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Simanang
 * @package Rangkotodotcom\Simanang\Facades
 */
class Simanang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simanang';
    }
}
