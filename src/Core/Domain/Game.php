<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 *
 * @UniqueEntity("name")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $id;

    /** @ORM\Column(type="string") */
    private string $name = '';

    /**
     * @ORM\OneToMany(
     *     targetEntity=Protocol::class,
     *     mappedBy="game",
     *     orphanRemoval=true,
     *     cascade={"ALL"}, fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"createdAt" = "ASC"})
     *
     * @var Collection<int,Protocol>
     */
    private Collection $protocol;

    /** @ORM\Column(type="datetime_immutable") */
    private DateTimeImmutable $createdAt;

    /** @ORM\Column(type="datetime_immutable", nullable=true) */
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
