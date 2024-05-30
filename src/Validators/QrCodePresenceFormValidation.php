<?php

namespace Rangkotodotcom\Simanang\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Simanang\Exceptions\SimanangValidationException;

class QrCodePresenceFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws SimanangValidationException
     */
    public static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'qrcode'   => 'bail|required|string'
        ]);

        if ($validator->fails()) {
            throw new SimanangValidationException($validator);
        }

        return  $validator->validate();
    }
}
