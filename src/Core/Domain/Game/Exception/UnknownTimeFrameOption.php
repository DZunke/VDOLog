<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Exception;

use DomainException;

final class UnknownTimeFrameOption extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forOption(string $option): UnknownTimeFrameOption
    {
        return new self('TimeFrame option wit name"' . $option . '" is not known');
    }
}
