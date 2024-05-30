<?php

namespace Rangkotodotcom\Simanang\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rangkotodotcom\Simanang getSchool()
 * @method static \Rangkotodotcom\Simanang getVision()
 * @method static \Rangkotodotcom\Simanang getMision()
 * @method static \Rangkotodotcom\Simanang getGallery()
 * @method static \Rangkotodotcom\Simanang getHeadMaster()
 * @method static \Rangkotodotcom\Simanang getCurrentSemester()
 * @method static \Rangkotodotcom\Simanang getStudent(string $param = null, array $data)
 * @method static \Rangkotodotcom\Simanang getTeacher(string $param = null, array $data)
 *
 * @see \Rangkotodotcom\Simanang
 */
class Simanang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simanang';
    }
}
