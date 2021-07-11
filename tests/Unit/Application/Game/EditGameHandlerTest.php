<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use PHPUnit\Framework\TestCase;
use VDOLog\Core\Application\Game\EditGame;
use VDOLog\Core\Application\Game\EditGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class EditGameHandlerTest extends TestCase
{
    public function testEditedGameIsSaved(): void
    {
        $game = self::createMock(Game::class);
        $game->expects(self::once())->method('setName')->with('foo');

        $gameRepositoryMock = self::createMock(GameRepository::class);
        $gameRepositoryMock->expects(self::once())->method('get')->willReturn($game);
        $gameRepositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $messageMock = self::createMock(EditGame::class);
        $messageMock->expects(self::once())->method('getId')->willReturn('12345');
        $messageMock->expects(self::once())->method('getName')->willReturn('foo');

        $handler = new EditGameHandler($gameRepositoryMock);
        $handler($messageMock);
    }
}
