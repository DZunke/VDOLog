<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Protocol\Exception;

use DomainException;

final class ProtocolNotFound extends DomainException
{
    public static function forId(string $id): ProtocolNotFound
    {
        return new self('A protocol entry with id "' . $id . '" could not be found');
    }
}
