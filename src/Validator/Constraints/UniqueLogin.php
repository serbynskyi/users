<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueLogin extends Constraint
{
    public string $message = 'Login "{{ value }}" is already used.';
}
