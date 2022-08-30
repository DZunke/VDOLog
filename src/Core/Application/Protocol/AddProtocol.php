<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Protocol;

use Assert\Assertion;
use VDOLog\Core\Domain\Protocol;

class AddProtocol
{
    private Protocol|null $parent;
    private string $sender    = '';
    private string $recipient = '';

    public function __construct(private string $gameId, private string $content)
    {
        Assertion::uuid($gameId, 'A game id must not be valid');
        Assertion::notBlank($content, 'A protocol entry must never be empty');
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getParent(): Protocol|null
    {
        return $this->parent;
    }

    public function setParent(Protocol|null $parent): void
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

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }
}
