<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Game;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\EditGame;
use VDOLog\Core\Domain\Game\TimeFrame;

final class EditGameTest extends TestCase
{
    public function testAnIdMustBeGiven(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game id must be given to edit it');

        new EditGame('', 'foo', self::createMock(TimeFrame::class));
    }

    public function testAnEmptyNameIsNotAccepted(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game must not get an empty name');

        new EditGame(Uuid::uuid4()->toString(), '', self::createMock(TimeFrame::class));
    }

    public function testMessageIsCreated(): void
    {
        $id = Uuid::uuid4()->toString();

        $message = new EditGame($id, 'foo', self::createMock(TimeFrame::class));

        self::assertSame($message->id, $id);
        self::assertSame($message->name, 'foo');
    }
}
