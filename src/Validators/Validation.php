<?php

namespace Rangkotodotcom\Simanang\Validators;

interface Validation
{
    /**
     * @param array $data
     * @return array
     */
    public static function validate(array $data): array;
}
