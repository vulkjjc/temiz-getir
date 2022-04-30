<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class City extends Constraint
{
    public string $message = 'The service "{{ string }}" is not valid.';
}
