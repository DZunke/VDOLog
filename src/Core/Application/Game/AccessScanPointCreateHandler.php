<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\LocationRepository;

final class AccessScanPointCreateHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly LocationRepository $locationRepository,
    ) {
    }

    public function __invoke(AccessScanPointCreate $message): void
    {
        $game = $this->gameRepository->get($message->gameId);

        $accessScanner = null;
        if ($message->accessScannerId !== null) {
            $accessScanner = $this->locationRepository->getAccessScanner($message->accessScannerId);
        }

        $game->createAccessScanPoint(
            $message->time,
            $message->entrances,
            $message->exits,
            $accessScanner,
        );

        $this->gameRepository->save($game);
    }
}
