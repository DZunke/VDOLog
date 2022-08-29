<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use VDOLog\Core\Domain\Common\Event\EventStore;
use VDOLog\Core\Domain\Common\Event\EventStoreable;
use VDOLog\Core\Domain\Game\AccessScanPoint;
use VDOLog\Core\Domain\Game\Event\GameCreated;
use VDOLog\Core\Domain\Game\Reminder;
use VDOLog\Core\Domain\Game\TimeFrame;
use VDOLog\Core\Domain\Location\AccessScanner;
use VDOLog\Core\Domain\User\UserCreatable;

/** @UniqueEntity("name") */
class Game implements EventStore
{
    use EventStoreable;
    use UserCreatable;

    private string $id;
    private string $name = '';

    private Location|null $location = null;
    private TimeFrame $timeFrame;

    /** @var Collection<int,Protocol> */
    private Collection $protocol;
    /** @var Collection<int,Reminder> */
    private Collection $reminder;
    /** @var Collection<int,AccessScanPoint> */
    private Collection $accessScanPoints;

    private DateTimeImmutable $createdAt;
    private DateTimeImmutable|null $closedAt = null;

    public function __construct()
    {
        $this->id               = Uuid::uuid4()->toString();
        $this->timeFrame        = TimeFrame::createFromDate(new DateTimeImmutable());
        $this->protocol         = new ArrayCollection();
        $this->reminder         = new ArrayCollection();
        $this->accessScanPoints = new ArrayCollection();
        $this->createdAt        = new DateTimeImmutable();
    }

    public static function create(string $name): Game
    {
        $game       = new self();
        $game->name = $name;

        $game->raiseEvent(new GameCreated($game));

        return $game;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLocation(): Location|null
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        if ($this->location !== null) {
            throw new RuntimeException('changing the location of the event is not possible');
        }

        $this->location = $location;
    }

    /** @return Collection<int,Protocol> */
    public function getProtocol(): Collection
    {
        return new ArrayCollection($this->protocol->toArray());
    }

    /** @return Collection<int,Reminder> */
    public function getReminder(): Collection
    {
        return new ArrayCollection($this->reminder->toArray());
    }

    public function removeReminder(Reminder $reminder): void
    {
        $this->reminder->removeElement($reminder);
    }

    /** @return array<AccessScanPoint> */
    public function getAccessScanPoints(): array
    {
        return $this->accessScanPoints->toArray();
    }

    public function createAccessScanPoint(
        DateTimeImmutable $time,
        int $entrances,
        int $exits,
        AccessScanner|null $accessScanner = null,
    ): void {
        if ($accessScanner !== null && $this->location === null) {
            throw new InvalidArgumentException('Game has no location so there could be no access scanner');
        }

        if (
            $accessScanner !== null
            && $this->location !== null
            && $this->location->getAccessScannerByName($accessScanner->getName()) === null
        ) {
            throw new InvalidArgumentException(
                'Access scanner "' . $accessScanner->getName() . '" does not exist for game',
            );
        }

        $scanPoint = AccessScanPoint::create($this, $time);
        $scanPoint->setEntrances($entrances);
        $scanPoint->setExits($exits);
        if ($accessScanner !== null) {
            $scanPoint->setAccessScanner($accessScanner);
        }

        $this->accessScanPoints->add($scanPoint);
    }

    public function getEntranceSum(AccessScanner|null $accessScanner = null): int
    {
        /** @var Collection<int,AccessScanPoint> $accessPoints */
        $accessPoints = $this->accessScanPoints->filter(
            static fn (AccessScanPoint $accessScanPoint) => $accessScanPoint->isFromAccessScanner($accessScanner)
        );

        $sum = 0;
        foreach ($accessPoints as $accessPoint) {
            $sum += $accessPoint->getEntrances();
        }

        return $sum;
    }

    public function getExitsSum(AccessScanner|null $accessScanner = null): int
    {
        /** @var Collection<int,AccessScanPoint> $accessPoints */
        $accessPoints = $this->accessScanPoints->filter(
            static fn (AccessScanPoint $accessScanPoint) => $accessScanPoint->isFromAccessScanner($accessScanner)
        );

        $sum = 0;
        foreach ($accessPoints as $accessPoint) {
            $sum += $accessPoint->getExits();
        }

        return $sum;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getClosedAt(): DateTimeImmutable|null
    {
        return $this->closedAt;
    }

    public function setClosedAt(DateTimeImmutable|null $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getTimeFrame(): TimeFrame
    {
        return $this->timeFrame;
    }

    public function setTimeFrame(TimeFrame $timeFrame): void
    {
        $this->timeFrame = $timeFrame;
    }

    public function addReminder(Reminder $reminder): void
    {
        $this->reminder->add($reminder);
    }
}
