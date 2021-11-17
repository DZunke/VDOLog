<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use Ramsey\Uuid\Uuid;
use VDOLog\Core\Application\Location\CreateLocation;
use VDOLog\Core\Application\Location\UpdateLocation;
use VDOLog\Core\Domain\Location;
use VDOLog\Core\Helper\Assertion;
use VDOLog\Web\Form\Dto\Location\AccessScannerDto;

class LocationDto
{
    private string $id  = '';
    public string $name = '';
    /** @var AccessScannerDto[] */
    public array $accessScanners = [];

    public static function fromLocation(Location $location): LocationDto
    {
        $dto       = new self();
        $dto->id   = $location->getId();
        $dto->name = $location->getName();

        foreach ($location->getAccessScanners() as $accessScanner) {
            $dto->accessScanners[] = AccessScannerDto::fromAccessScanner($accessScanner);
        }

        return $dto;
    }

    public function toCreateCommand(): CreateLocation
    {
        $scanners = [];
        foreach ($this->accessScanners as $scanner) {
            $scanners[] = $scanner->name;
        }

        return new CreateLocation($this->name, $scanners);
    }

    public function toUpdateCommand(): UpdateLocation
    {
        Assertion::uuid($this->id);

        $scanners = [];
        foreach ($this->accessScanners as $scanner) {
            $scanners[$scanner->id ?? Uuid::uuid4()->toString()] = $scanner->name;
        }

        return new UpdateLocation($this->id, $this->name, $scanners);
    }
}
