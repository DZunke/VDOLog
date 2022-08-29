<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use VDOLog\Core\Domain\Location;
use VDOLog\Core\Domain\Location\AccessScanner;
use VDOLog\Core\Domain\LocationRepository;

class DoctrineLocationRepository implements LocationRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @return Collection<int,Location> */
    public function findAll(): Collection
    {
        return new ArrayCollection($this->em->getRepository(Location::class)->findAll());
    }

    public function get(string $id): Location
    {
        $location = $this->em->getRepository(Location::class)->find($id);
        if ($location === null) {
            throw new InvalidArgumentException('Not found', 404);
        }

        return $location;
    }

    public function save(Location $location): void
    {
        if (! $this->em->contains($location)) {
            $this->em->persist($location);
        }

        $this->em->flush();
    }

    public function getAccessScanner(string $accessScannerId): AccessScanner
    {
        $accessScanner = $this->em->getRepository(AccessScanner::class)->find($accessScannerId);
        if ($accessScanner === null) {
            throw new InvalidArgumentException('Scanner does not exist');
        }

        return $accessScanner;
    }
}
