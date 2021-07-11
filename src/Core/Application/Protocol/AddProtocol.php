<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Protocol;

use Assert\Assertion;
use VDOLog\Core\Domain\Protocol;

final class AddProtocol
{
    private string $gameId;
    private string $content;

    private ?Protocol $parent;
    private string $sender   = '';
    private string $recipent = '';

    public function __construct(string $gameId, string $content)
    {
        Assertion::notBlank($gameId, 'A game must always be given');
        Assertion::notBlank($content, 'A protocol entry must never be empty');

        $this->gameId  = $gameId;
        $this->content = $content;
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getParent(): ?Protocol
    {
        return $this->parent;
    }

    public function setParent(?Protocol $parent): void
    {
        $this->parent = $parent;
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

    public function setRecipent(string $recipient): void
    {
        $this->recipent = $recipient;
    }
}
