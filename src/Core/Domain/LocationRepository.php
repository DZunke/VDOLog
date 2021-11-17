<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Doctrine\Common\Collections\Collection;

interface LocationRepository
{
    public function get(string $id): Location;

    public function save(Location $location): void;

    /** @return Collection<int,Location> */
    public function findAll(): Collection;
}
