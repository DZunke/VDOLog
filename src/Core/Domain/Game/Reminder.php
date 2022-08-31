<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Helper\Assertion;

class Reminder
{
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable|null $sentAt;
    private DateTimeImmutable|null $seenAt;

    public function __construct(
        private readonly string $id,
        private readonly Game $game,
        private readonly string $title,
        private readonly string $message,
        private readonly string $remindAt,
    ) {
        Assertion::uuid($id);
        Assertion::notBlank($title);
        Assertion::notBlank($message);
        Assertion::relativeDateTimeString($remindAt);

        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(Game $game, string $title, string $message, string $remindAt): Reminder
    {
        return new self(Uuid::uuid4()->toString(), $game, $title, $message, $remindAt);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRemindAt(): string
    {
        return $this->remindAt;
    }

    public function sent(): void
    {
        $this->sentAt = new DateTimeImmutable();
    }

    public function isSent(): bool
    {
        return $this->sentAt !== null;
    }

    public function seen(): void
    {
        $this->seenAt = new DateTimeImmutable();
    }

    public function isSeen(): bool
    {
        return $this->seenAt !== null;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRemindAtAsDate(): DateTimeImmutable
    {
        return $this->game->getTimeFrame()->getEventStartsAt()->modify($this->remindAt);
    }
}
