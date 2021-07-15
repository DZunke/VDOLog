<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use Assert\InvalidArgumentException;
use Symfony\Component\Form\Test\TypeTestCase;
use VDOLog\Core\Application\Game\CreateGame;

final class CreateGameTest extends TypeTestCase
{
    public function testMessageCouldNotBeCreatedWithoutName(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game must have a name');

        new CreateGame('');
    }

    public function testMessageCouldBeCreated(): void
    {
        $message = new CreateGame('foo');

        self::assertSame($message->getName(), 'foo');
    }
}
