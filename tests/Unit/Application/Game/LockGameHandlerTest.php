<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\LockGame;
use VDOLog\Core\Application\Game\LockGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class LockGameHandlerTest extends TestCase
{
    public function testLOckingIsDone(): void
    {
        $id = Uuid::uuid4()->toString();

        $gameMock = self::createMock(Game::class);
        $gameMock->expects(self::once())->method('setClosedAt')->with(self::isInstanceOf(DateTimeImmutable::class));

        $gameRepositoryMock = self::createMock(GameRepository::class);
        $gameRepositoryMock->expects(self::once())->method('get')->with($id)->willReturn($gameMock);
        $gameRepositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $messageMock = self::createMock(LockGame::class);
        $messageMock->expects(self::once())->method('getId')->willReturn($id);

        $handler = new LockGameHandler($gameRepositoryMock);
        $handler($messageMock);
    }
}
