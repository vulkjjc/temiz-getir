<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProvinceId extends Constraint
{
    public string $message = 'The province "{{ string }}" is not valid.';
}
