<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Common\SynchronizedCollection;
use VDOLog\Core\Domain\Common\SynchronizedCollection\DynamicSynchronizationPolicy;
use VDOLog\Core\Domain\Location\AccessScanner;
use VDOLog\Core\Domain\LocationRepository;
use VDOLog\Core\Domain\User\CurrentUserProvider;

final class UpdateLocationHandler implements MessageHandlerInterface
{
    public function __construct(
        private LocationRepository $locationRepository,
        private CurrentUserProvider $currentUserProvider,
    ) {
    }

    public function __invoke(UpdateLocation $message): void
    {
        $location = $this->locationRepository->get($message->getId());
        $location->setEditedBy($this->currentUserProvider->getCurrentUser());
        $location->rename($message->getName());

        $updatedAccessScanners = $message->getAccessScanners();
        $parsedAccessScanners  = [];
        foreach ($updatedAccessScanners as $id => $name) {
            $parsedAccessScanners[$id] = new AccessScanner($id, $location, $name);
        }

        $policy = new DynamicSynchronizationPolicy(
            static fn (AccessScanner $data) => $location->addAccessScanner($data),
            static fn (AccessScanner $origin, AccessScanner $data) => $origin->rename($data->getName()),
            static fn (AccessScanner $origin) => $location->removeAccessScanner($origin)
        );

        $cartCollection = new SynchronizedCollection($location->getAccessScanners(), $policy);
        $cartCollection->sync(
            $parsedAccessScanners,
            static fn ($newProduct, $product) => $product->equals($newProduct)
        );

        $this->locationRepository->save($location);
    }
}
