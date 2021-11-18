<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\LocationRepository;

final class AccessScanPointCreateHandler implements MessageHandlerInterface
{
    public function __construct(
        private GameRepository $gameRepository,
        private LocationRepository $locationRepository
    ) {
    }

    public function __invoke(AccessScanPointCreate $message): void
    {
        $game = $this->gameRepository->get($message->getGameId());

        $accessScanner = null;
        if ($message->getAccessScannerId() !== null) {
            $accessScanner = $this->locationRepository->getAccessScanner($message->getAccessScannerId());
        }

        $game->createAccessScanPoint(
            $message->getTime(),
            $message->getEntrances(),
            $message->getExits(),
            $accessScanner
        );

        $this->gameRepository->save($game);
    }
}
