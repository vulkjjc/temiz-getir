<?php

namespace App\Validator\Service;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Ironing extends Constraint
{
    public string $message = 'The city "{{ string }}" is not valid.';
}
