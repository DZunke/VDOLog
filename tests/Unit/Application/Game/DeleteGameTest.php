<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\DeleteGame;

final class DeleteGameTest extends TestCase
{
    public function testMessageCouldNotBeDeletedWithoutAnId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('To delete a game a valid id must be given');

        new DeleteGame('foo');
    }

    public function testMessageCouldBeCreated(): void
    {
        $id      = Uuid::uuid4()->toString();
        $message = new DeleteGame($id);

        self::assertSame($message->getId(), $id);
    }
}
