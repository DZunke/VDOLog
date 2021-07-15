<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class LockGameHandler implements MessageHandlerInterface
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(LockGame $message): void
    {
        $game = $this->gameRepository->get($message->getId());
        $game->setClosedAt(new DateTimeImmutable());
        $this->gameRepository->save($game);
    }
}
