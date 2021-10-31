<?php

declare(strict_types=1);

namespace VDOLog\Web\Validator;

use Symfony\Component\Validator\Constraint;

final class DateTimeRelativeString extends Constraint
{
    public string $message = 'The string "{{ value }}" is not a valid relative datetime string';
}
