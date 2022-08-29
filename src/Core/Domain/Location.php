<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Assert\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Stringable;
use VDOLog\Core\Domain\Location\AccessScanner;
use VDOLog\Core\Domain\User\UserCreatable;
use VDOLog\Core\Domain\User\UserEditable;

class Location implements Stringable
{
    use UserCreatable;
    use UserEditable;

    /** @var Collection<string, AccessScanner> */
    private Collection $accessScanners;

    public function __construct(private string $id, private string $name)
    {
        Assertion::uuid($id);
        Assertion::notBlank($name);

        $this->accessScanners = new ArrayCollection();
    }

    public static function create(string $name): Location
    {
        return new self(Uuid::uuid4()->toString(), $name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        Assertion::notBlank($name);

        $this->name = $name;
    }

    /** @return array<string, AccessScanner> */
    public function getAccessScanners(): array
    {
        return $this->accessScanners->toArray();
    }

    public function removeAccessScanner(AccessScanner $accessScanner): void
    {
        if (! $this->accessScanners->contains($accessScanner)) {
            return;
        }

        $this->accessScanners->removeElement($accessScanner);
    }

    public function addAccessScanner(AccessScanner $accessScanner): void
    {
        if ($this->accessScanners->contains($accessScanner)) {
            return;
        }

        $this->accessScanners->add($accessScanner);
    }

    public function getAccessScannerByName(string $name): AccessScanner|null
    {
        foreach ($this->accessScanners as $scanner) {
            if ($scanner->getName() === $name) {
                return $scanner;
            }
        }

        return null;
    }

    public function createAccessScanner(string $name): void
    {
        if ($this->getAccessScannerByName($name) !== null) {
            return;
        }

        $this->accessScanners->add(AccessScanner::create($this, $name));
    }
}
