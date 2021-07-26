<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use VDOLog\Core\Domain\Common\Event\EventStore;
use VDOLog\Core\Domain\Common\Event\EventStoreable;
use VDOLog\Core\Domain\Game\Event\GameCreated;
use VDOLog\Core\Domain\Game\Reminder;
use VDOLog\Core\Domain\Game\TimeFrame;

/**
 * @UniqueEntity("name")
 */
class Game implements EventStore
{
    use EventStoreable;

    private string $id;
    private string $name = '';

    private TimeFrame $timeFrame;

    /** @var Collection<int,Protocol> */
    private Collection $protocol;
    /** @var Collection<int,Reminder> */
    private Collection $reminder;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $closedAt = null;

    public function __construct()
    {
        $this->id        = Uuid::uuid4()->toString();
        $this->timeFrame = TimeFrame::createFromDate(new DateTimeImmutable());
        $this->protocol  = new ArrayCollection();
        $this->reminder  = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
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

    /**
     * @return Collection<int,Protocol>
     */
    public function getProtocol(): Collection
    {
        return new ArrayCollection($this->protocol->toArray());
    }

    /**
     * @return Collection<int,Reminder>
     */
    public function getReminder(): Collection
    {
        return new ArrayCollection($this->reminder->toArray());
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getClosedAt(): ?DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?DateTimeImmutable $closedAt): void
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
