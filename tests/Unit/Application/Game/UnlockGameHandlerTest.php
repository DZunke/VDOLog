<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\UnlockGame;
use VDOLog\Core\Application\Game\UnlockGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class UnlockGameHandlerTest extends TestCase
{
    public function testUnlckingIsDone(): void
    {
        $id = Uuid::uuid4()->toString();

        $gameMock = self::createMock(Game::class);
        $gameMock->expects(self::once())->method('setClosedAt')->with(null);

        $gameRepositoryMock = self::createMock(GameRepository::class);
        $gameRepositoryMock->expects(self::once())->method('get')->with($id)->willReturn($gameMock);
        $gameRepositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $messageMock = self::createMock(UnlockGame::class);
        $messageMock->expects(self::once())->method('getId')->willReturn($id);

        $handler = new UnlockGameHandler($gameRepositoryMock);
        $handler($messageMock);
    }
}
