<?php

namespace Rangkotodotcom\Simanang\Validator;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Simanang\Exceptions\SimanangValidationException;

class PresenceFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws SimanangValidationException
     */
    public static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'qr_code' => 'bail|required|string',
        ]);

        if ($validator->fails()) {
            throw new SimanangValidationException($validator);
        }

        return  $validator->validate();
    }
}
