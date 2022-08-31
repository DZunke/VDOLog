<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Game;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
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

        $handler = new EditGameHandler($gameRepositoryMock);
        $handler(new EditGame(Uuid::uuid1()->toString(), 'foo', $this->createMock(Game\TimeFrame::class)));
    }
}
