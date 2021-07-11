<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Exception;

use DomainException;

final class GameNotFound extends DomainException
{
    public static function forId(string $id): GameNotFound
    {
        return new self('A game with id "' . $id . '" could not be found');
    }
}
