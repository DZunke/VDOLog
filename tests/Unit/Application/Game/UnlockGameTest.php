<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\UnlockGame;

final class UnlockGameTest extends TestCase
{
    public function testCreationFailsWithoutId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A valid game id must be given');

        new UnlockGame('ffsadas');
    }

    public function testCreationWillWork(): void
    {
        $id      = Uuid::uuid4()->toString();
        $message = new UnlockGame($id);

        self::assertSame($message->getId(), $id);
    }
}
