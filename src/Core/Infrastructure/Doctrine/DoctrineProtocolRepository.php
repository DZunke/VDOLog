<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Protocol;
use VDOLog\Core\Domain\Protocol\Exception\ProtocolNotFound;
use VDOLog\Core\Domain\ProtocolRepository;

class DoctrineProtocolRepository implements ProtocolRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function get(string $id): Protocol
    {
        $protocol = $this->em->find(Protocol::class, $id);
        if ($protocol === null) {
            throw ProtocolNotFound::forId($id);
        }

        return $protocol;
    }

    /** @return Collection<int,Protocol> */
    public function findForListing(Game $game): Collection
    {
        return new ArrayCollection(
            $this->em->getRepository(Protocol::class)->findBy(
                [
                    'parent' => null,
                    'game' => $game,
                ],
                ['createdAt' => 'desc'],
            ),
        );
    }

    public function save(Protocol $protocol): void
    {
        if (! $this->em->contains($protocol)) {
            $this->em->persist($protocol);
        }

        $this->em->flush();
    }
}
