<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("name")
 */
class Game
{
    private string $id;
    private string $name = '';

    /** @var Collection<int,Protocol> */
    private Collection $protocol;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $closedAt = null;

    public function __construct()
    {
        $this->id        = Uuid::uuid4()->toString();
        $this->protocol  = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(string $name): Game
    {
        $game       = new self();
        $game->name = $name;

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
}
