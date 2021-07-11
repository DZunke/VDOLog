<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\LockGame;

final class LockGameTest extends TestCase
{
    public function testCreationWillFailWithoutId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A valid game id must be given');

        new LockGame('foo');
    }

    public function testMessageCouldBeCreated(): void
    {
        $id      = Uuid::uuid4()->toString();
        $message = new LockGame($id);

        self::assertSame($message->getId(), $id);
    }
}
