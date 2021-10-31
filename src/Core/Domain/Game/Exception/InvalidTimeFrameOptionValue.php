<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Exception;

use DomainException;

final class InvalidTimeFrameOptionValue extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forValue(string $value): InvalidTimeFrameOptionValue
    {
        return new self('The value "' . $value . '" is not a valid date modification string.');
    }
}
