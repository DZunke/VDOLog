<?php

declare(strict_types=1);

namespace VDOLog\Web\Validator;

use Symfony\Component\Validator\Constraint;

final class UniqueEntity extends Constraint
{
    public const NOT_UNIQUE_ERROR = '71161774-fb61-4ce1-85e7-0da44b21f82b';

    public string $message     = 'This value is already used.';
    public string $entityClass = '';
    public string $field       = '';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }
}
