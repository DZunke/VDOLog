<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VDOLog\Core\Domain\Common\Event\DomainEvent;
use VDOLog\Core\Domain\Common\Event\EventStore;

use function array_merge;

final class DoctrineEventStoreHandler implements EventSubscriber
{
    private EventDispatcherInterface $eventDispatcher;

    /** @var DomainEvent[] */
    private array $eventStoreCollection = [];

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /** @inheritDoc */
    public function getSubscribedEvents(): array
    {
        return [
            'onFlush',
            'postFlush',
        ];
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em  = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->collectEventStoreEvents($uow->getScheduledEntityInsertions());
        $this->collectEventStoreEvents($uow->getScheduledEntityUpdates());
        $this->collectEventStoreEvents($uow->getScheduledEntityDeletions());
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $events                     = $this->eventStoreCollection;
        $this->eventStoreCollection = [];

        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    /**
     * @param array<string,object> $entities
     */
    private function collectEventStoreEvents(array $entities): void
    {
        foreach ($entities as $entity) {
            if (! $entity instanceof EventStore) {
                continue;
            }

            $this->eventStoreCollection = array_merge($this->eventStoreCollection, $entity->flushEvents());
        }
    }
}
