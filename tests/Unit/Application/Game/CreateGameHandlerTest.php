<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use PHPUnit\Framework\TestCase;
use VDOLog\Core\Application\Game\CreateGame;
use VDOLog\Core\Application\Game\CreateGameHandler;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class CreateGameHandlerTest extends TestCase
{
    public function testGameWillBeSaved(): void
    {
        $repositoryMock = self::createMock(GameRepository::class);
        $repositoryMock->expects(self::once())->method('save')->with(self::isInstanceOf(Game::class));

        $messageMock = self::createMock(CreateGame::class);
        $messageMock->expects(self::once())->method('getName')->willReturn('foo');

        $handler = new CreateGameHandler($repositoryMock);
        $handler($messageMock);
    }
}
