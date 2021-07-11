<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Doctrine\Common\Collections\Collection;

interface ProtocolRepository
{
    /**
     * @return Collection<int,Protocol>
     */
    public function findForListing(Game $game): Collection;

    public function save(Protocol $protocol): void;
}
