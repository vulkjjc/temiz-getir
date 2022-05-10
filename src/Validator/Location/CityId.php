<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CityId extends Constraint
{
    public string $message = 'The service "{{ string }}" is not valid.';
}
