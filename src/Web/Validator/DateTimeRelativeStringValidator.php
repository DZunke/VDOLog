<?php

declare(strict_types=1);

namespace VDOLog\Web\Validator;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Throwable;

use function is_string;
use function strlen;

final class DateTimeRelativeStringValidator extends ConstraintValidator
{
    /** @inheritDoc */
    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof DateTimeRelativeString) {
            return;
        }

        if (! is_string($value) || strlen($value) === 0) {
            return;
        }

        try {
            new DateTime($value);
        } catch (Throwable) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
