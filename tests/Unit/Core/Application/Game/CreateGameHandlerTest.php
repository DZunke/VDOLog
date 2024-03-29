<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Application\Game;

use PHPUnit\Framework\TestCase;
use VDOLog\Core\Application\Game\CreateGame;
use VDOLog\Core\Application\Game\CreateGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\User\CurrentUserProvider;

final class CreateGameHandlerTest extends TestCase
{
    public function testGameWillBeSaved(): void
    {
        $repositoryMock = self::createMock(GameRepository::class);
        $repositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $currentUserMock = self::createMock(CurrentUserProvider::class);
        $currentUserMock->expects(self::once())->method('hasCurrentUser')->willReturn(false);

        $handler = new CreateGameHandler($repositoryMock, $currentUserMock);
        $handler(new CreateGame('foo', $this->createMock(Game\TimeFrame::class)));
    }
}
