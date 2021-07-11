<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Application\Game;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Game\DeleteGame;
use VDOLog\Core\Application\Game\DeleteGameHandler;
use VDOLog\Core\Domain\GameRepository;

final class DeleteGameHandlerTest extends TestCase
{
    public function testTheGameIsDeleted(): void
    {
        $id = Uuid::uuid4()->toString();

        $gameRepositoryMock = self::createMock(GameRepository::class);
        $gameRepositoryMock->expects(self::once())->method('delete')->with($id);

        $messageMock = self::createMock(DeleteGame::class);
        $messageMock->expects(self::once())->method('getId')->willReturn($id);

        $handler = new DeleteGameHandler($gameRepositoryMock);
        $handler($messageMock);
    }
}
