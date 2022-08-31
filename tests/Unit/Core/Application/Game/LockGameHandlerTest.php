<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Game;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\LockGame;
use VDOLog\Core\Application\Game\LockGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class LockGameHandlerTest extends TestCase
{
    public function testLockingIsDone(): void
    {
        $id = Uuid::uuid4()->toString();

        $gameMock = self::createMock(Game::class);
        $gameMock->expects(self::once())->method('setClosedAt')->with(self::isInstanceOf(DateTimeImmutable::class));

        $gameRepositoryMock = self::createMock(GameRepository::class);
        $gameRepositoryMock->expects(self::once())->method('get')->with($id)->willReturn($gameMock);
        $gameRepositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $handler = new LockGameHandler($gameRepositoryMock);
        $handler(new LockGame($id));
    }
}
