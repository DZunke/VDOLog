<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Exception;

use DomainException;

final class GameNotFound extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forId(string $id): GameNotFound
    {
        return new self('A game with id "' . $id . '" could not be found');
    }
}
