<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CountryId extends Constraint
{
    public string $message = 'The country "{{ string }}" is not valid.';
}
