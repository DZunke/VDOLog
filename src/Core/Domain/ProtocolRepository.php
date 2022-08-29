<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Doctrine\Common\Collections\Collection;
use VDOLog\Core\Domain\Protocol\Exception\ProtocolNotFound;

interface ProtocolRepository
{
    /** @throws ProtocolNotFound if a protocol entry with the given id could not be found. */
    public function get(string $id): Protocol;

    /** @return Collection<int,Protocol> */
    public function findForListing(Game $game): Collection;

    public function save(Protocol $protocol): void;
}
