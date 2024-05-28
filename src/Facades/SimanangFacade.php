<?php

namespace Rangkotodotcom\Simanang;

use Illuminate\Support\Facades\Facade;

/**
 * Class SimanangFacade
 * @package Rangkotodotcom\Simanang\Facades
 */
class SimanangFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simanang';
    }
}
