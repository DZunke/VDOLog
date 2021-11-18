<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Doctrine\Common\Collections\Collection;
use VDOLog\Core\Domain\Location\AccessScanner;

interface LocationRepository
{
    public function get(string $id): Location;

    public function save(Location $location): void;

    /** @return Collection<int,Location> */
    public function findAll(): Collection;

    public function getAccessScanner(string $accessScannerId): AccessScanner;
}
