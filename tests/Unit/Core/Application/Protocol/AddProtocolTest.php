<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Protocol;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Protocol\AddProtocol;

final class AddProtocolTest extends TestCase
{
    public function testConstructionFailsWihtoutGameId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A game id must not be valid');

        new AddProtocol('fsadas', 'fofsdfsd');
    }

    public function testConstructionFailsWithEmptyContent(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('A protocol entry must never be empty');

        new AddProtocol(Uuid::uuid4()->toString(), '');
    }

    public function testConstructionIsWorking(): void
    {
        $id      = Uuid::uuid4()->toString();
        $content = 'foo bar baz';

        $message = new AddProtocol($id, $content);

        self::assertSame($id, $message->getGameId());
        self::assertSame($content, $message->getContent());
    }
}
