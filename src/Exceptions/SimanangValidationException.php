<?php

namespace Rangkotodotcom\Simanang\Exceptions;

use Illuminate\Validation\ValidationException;

class SimanangValidationException extends ValidationException
{

    public function getErrorBags(): ?array
    {
        return $this->errors();
    }
}
