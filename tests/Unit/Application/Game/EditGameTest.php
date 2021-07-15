<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\EditGame;

final class EditGameTest extends TestCase
{
    public function testAnIdMustBeGiven(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game id must be given to edit it');

        new EditGame('', 'foo');
    }

    public function testAnEmptyNameIsNotAccepted(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game must not get an empty name');

        new EditGame(Uuid::uuid4()->toString(), '');
    }

    public function testMessageIsCreated(): void
    {
        $id = Uuid::uuid4()->toString();

        $message = new EditGame($id, 'foo');

        self::assertSame($message->getId(), $id);
        self::assertSame($message->getName(), 'foo');
    }
}
