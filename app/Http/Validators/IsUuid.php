<?php

namespace App\Http\Validators;

use Ramsey\Uuid\Uuid;

class IsUuid
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return Uuid::isValid($value);
    }
}