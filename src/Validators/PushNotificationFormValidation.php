<?php

namespace Rangkotodotcom\Simanang\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Simanang\Exceptions\SimanangValidationException;

class PushNotificationFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws SimanangValidationException
     */
    public static function validate(array $data): array
    {
        if (config('simanang.simanang_mode') == 'production') {
            $topic = 'announcement_all,announcement_pd,announcement_ptk';
        } else {
            $topic = 'announcement_all_dev,announcement_pd_dev,announcement_ptk_dev';
        }

        $rules = [
            'type'              => 'bail|required|in:broadcast,spesific',
            'notif'             => 'bail|required|array',
            'notif.type'        => 'bail|required|max:4',
            'notif.title'       => 'bail|required',
            'notif.body'        => 'bail|required',
            'notif.content'     => 'bail|required|array',
            'notif.content.id'  => 'bail|required',
        ];

        if ($data['type'] == 'spesific') {
            $rules['for'] = 'required|in:student,teacher';
            $rules['simanang_id'] = 'required|uuid';
        } else if ($data['type'] == 'broadcast') {
            $rules['topic'] = "required|in:{$topic}";
        }


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new SimanangValidationException($validator);
        }

        return  $validator->validate();
    }
}
