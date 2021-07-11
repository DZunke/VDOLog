<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Protocol
{
    private string $id;
    private Game $game;
    private ?Protocol $parent;
    /** @var Collection<int,Protocol> */
    private Collection $children;
    private string $content  = '';
    private string $sender   = '';
    private string $recipent = '';
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->id        = Uuid::uuid4()->toString();
        $this->children  = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(Game $game, string $content): Protocol
    {
        $protocol          = new self();
        $protocol->game    = $game;
        $protocol->content = $content;

        return $protocol;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getParent(): ?Protocol
    {
        return $this->parent;
    }

    public function setParent(?Protocol $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection<int,Protocol>
     */
    public function getChildren(): Collection
    {
        return new ArrayCollection($this->children->toArray());
    }

    public function addChild(Protocol $protocol): void
    {
        $protocol->parent = $this;
        $this->children->add($protocol);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function getRecipent(): string
    {
        return $this->recipent;
    }

    public function setRecipent(string $recipent): void
    {
        $this->recipent = $recipent;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
