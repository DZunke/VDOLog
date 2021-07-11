<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Protocol;
use VDOLog\Core\Domain\ProtocolRepository;

/**
 * @extends EntityRepository<Protocol>
 */
class DoctrineProtocolRepository extends EntityRepository implements ProtocolRepository
{
    /**
     * @return Collection<int,Protocol>
     */
    public function findForListing(Game $game): Collection
    {
        return new ArrayCollection(
            $this->findBy(
                [
                    'parent' => null,
                    'game' => $game,
                ],
                ['createdAt' => 'desc']
            )
        );
    }
}
