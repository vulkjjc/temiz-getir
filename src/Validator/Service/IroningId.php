<?php

namespace App\Validator\Service;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IroningId extends Constraint
{
    public string $message = 'The city "{{ string }}" is not valid.';
}
