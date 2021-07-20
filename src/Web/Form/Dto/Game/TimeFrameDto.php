<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto\Game;

use DateTimeImmutable;
use VDOLog\Core\Domain\Game\TimeFrame;

class TimeFrameDto
{
    public DateTimeImmutable $eventStartsAt;
    public string $optSpectatorEntry = '+2 hours';
    public string $optEventActBegin  = '+4 hours +30 minutes';
    public string $optEventActEnd    = '+7 hours +15 minutes';

    public function __construct()
    {
        $this->eventStartsAt = new DateTimeImmutable();
    }

    public static function fromTimeFrame(TimeFrame $timeFrame): TimeFrameDto
    {
        $obj                    = new self();
        $obj->eventStartsAt     = $timeFrame->getEventStartsAt();
        $obj->optSpectatorEntry = $timeFrame->getOption(TimeFrame::OPT_SPECTATOR_ENTRY);
        $obj->optEventActBegin  = $timeFrame->getOption(TimeFrame::OPT_EVENT_ACT_BEGIN);
        $obj->optEventActEnd    = $timeFrame->getOption(TimeFrame::OPT_EVENT_ACT_END);

        return $obj;
    }

    public function toTimeFrame(): TimeFrame
    {
        $timeFrame = TimeFrame::createFromDate($this->eventStartsAt);
        $timeFrame->setOption(TimeFrame::OPT_SPECTATOR_ENTRY, $this->optSpectatorEntry);
        $timeFrame->setOption(TimeFrame::OPT_EVENT_ACT_BEGIN, $this->optEventActBegin);
        $timeFrame->setOption(TimeFrame::OPT_EVENT_ACT_END, $this->optEventActEnd);

        return $timeFrame;
    }
}
