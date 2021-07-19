<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game;

use DateTimeImmutable;
use Throwable;
use VDOLog\Core\Domain\Game\Exception\InvalidTimeFrameOptionValue;
use VDOLog\Core\Domain\Game\Exception\UnknownTimeFrameOption;

use function array_key_exists;

class TimeFrame
{
    public const OPT_SPECTATOR_ENTRY = 'spectator_entry_start';
    public const OPT_EVENT_ACT_BEGIN = 'event_act_begin';
    public const OPT_EVENT_ACT_END   = 'event_act_end';

    private DateTimeImmutable $eventStartsAt;

    /** @var array<string,string> */
    private array $timeframeOptions = [
        self::OPT_SPECTATOR_ENTRY => '+2 hours',
        self::OPT_EVENT_ACT_BEGIN => '+4 hours +30 minutes',
        self::OPT_EVENT_ACT_END => '+7 hours +15 minutes',
    ];

    private function __construct()
    {
    }

    public static function create(): TimeFrame
    {
        return self::createFromDate(new DateTimeImmutable());
    }

    public static function createFromDate(DateTimeImmutable $eventStartDate): TimeFrame
    {
        $timeframe                = new self();
        $timeframe->eventStartsAt = $eventStartDate;

        return $timeframe;
    }

    public function getEventStartsAt(): DateTimeImmutable
    {
        return $this->eventStartsAt;
    }

    public function setEventStartsAt(DateTimeImmutable $eventStartsAt): void
    {
        $this->eventStartsAt = $eventStartsAt;
    }

    public function hasOption(string $option): bool
    {
        return array_key_exists($option, $this->timeframeOptions);
    }

    public function getOption(string $option): string
    {
        if (! $this->hasOption($option)) {
            throw UnknownTimeFrameOption::forOption($option);
        }

        return $this->timeframeOptions[$option];
    }

    public function getOptionAsDateTime(string $option): DateTimeImmutable
    {
        return $this->eventStartsAt->modify($this->getOption($option));
    }

    public function setOption(string $option, string $value): void
    {
        if (! $this->hasOption($option)) {
            throw UnknownTimeFrameOption::forOption($option);
        }

        try {
            new DateTimeImmutable($value);
        } catch (Throwable) {
            throw InvalidTimeFrameOptionValue::forValue($value);
        }

        $this->timeframeOptions[$option] = $value;
    }
}
