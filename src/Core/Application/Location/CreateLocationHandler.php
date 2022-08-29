<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Location;
use VDOLog\Core\Domain\LocationRepository;
use VDOLog\Core\Domain\User\CurrentUserProvider;

final class CreateLocationHandler implements MessageHandlerInterface
{
    public function __construct(
        private LocationRepository $locationRepository,
        private CurrentUserProvider $currentUserProvider,
    ) {
    }

    public function __invoke(CreateLocation $message): void
    {
        $location = Location::create($message->getName());
        foreach ($message->getAccessScanners() as $accessScanner) {
            $location->createAccessScanner($accessScanner);
        }

        if ($this->currentUserProvider->hasCurrentUser()) {
            $location->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->locationRepository->save($location);
    }
}
