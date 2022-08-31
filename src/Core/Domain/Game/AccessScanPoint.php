<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Location\AccessScanner;
use VDOLog\Core\Helper\Assertion;

class AccessScanPoint
{
    private AccessScanner|null $accessScanner = null;

    private int $entrances = 0;
    private int $exits     = 0;

    public function __construct(
        private readonly string $id,
        private readonly Game $game,
        private readonly DateTimeImmutable $time,
    ) {
        Assertion::uuid($id);
    }

    public static function create(Game $game, DateTimeImmutable $time): AccessScanPoint
    {
        return new self(Uuid::uuid4()->toString(), $game, $time);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getAccessScanner(): AccessScanner|null
    {
        return $this->accessScanner;
    }

    public function isFromAccessScanner(AccessScanner|null $accessScanner = null): bool
    {
        if (! $accessScanner instanceof AccessScanner) {
            return $this->accessScanner === null;
        }

        // @phpstan-ignore-next-line cause you are a fool - call on accessScanner cause it is really not null here!
        return $this->accessScanner->equals($accessScanner);
    }

    public function setAccessScanner(AccessScanner $accessScanner): void
    {
        Assertion::null($this->accessScanner, 'Assigned access scanner could not be changed');

        $this->accessScanner = $accessScanner;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    public function getEntrances(): int
    {
        return $this->entrances;
    }

    public function setEntrances(int $entrances): void
    {
        $this->entrances = $entrances;
    }

    public function getExits(): int
    {
        return $this->exits;
    }

    public function setExits(int $exits): void
    {
        $this->exits = $exits;
    }
}
