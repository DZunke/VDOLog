<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Game;

use Assert\InvalidArgumentException;
use Symfony\Component\Form\Test\TypeTestCase;
use VDOLog\Core\Application\Game\CreateGame;
use VDOLog\Core\Domain\Game\TimeFrame;

final class CreateGameTest extends TypeTestCase
{
    public function testMessageCouldNotBeCreatedWithoutName(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game must have a name');

        new CreateGame('', self::createMock(TimeFrame::class));
    }

    public function testMessageCouldBeCreated(): void
    {
        $message = new CreateGame('foo', self::createMock(TimeFrame::class));

        self::assertSame($message->name, 'foo');
    }
}
