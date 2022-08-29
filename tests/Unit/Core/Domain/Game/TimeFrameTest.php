<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Domain\Game;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VDOLog\Core\Domain\Game\Exception\InvalidTimeFrameOptionValue;
use VDOLog\Core\Domain\Game\Exception\UnknownTimeFrameOption;
use VDOLog\Core\Domain\Game\TimeFrame;

final class TimeFrameTest extends TestCase
{
    public function testCreateInstanceFromDate(): void
    {
        $date      = new DateTimeImmutable();
        $timeFrame = TimeFrame::createFromDate($date);

        self::assertSame($date->format('Y-m-d H:i:s'), $timeFrame->getEventStartsAt()->format('Y-m-d H:i:s'));
    }

    public function testOptionsDateTimeIsCalculated(): void
    {
        $date      = (new DateTimeImmutable())->setTime(12, 15);
        $timeFrame = TimeFrame::createFromDate($date);
        $timeFrame->setOption(TimeFrame::OPT_EVENT_ACT_END, '+3 hours +30 minutes');

        $optEventActEnd = $timeFrame->getOptionAsDateTime(TimeFrame::OPT_EVENT_ACT_END);

        self::assertSame(
            $date->format('Y-m-d') . ' 15:45:00',
            $optEventActEnd->format('Y-m-d H:i:s'),
        );
    }

    public function testGetUnknownOptionWillThrowException(): void
    {
        self::expectException(UnknownTimeFrameOption::class);

        $timeframe = TimeFrame::create();
        $timeframe->getOption('foo');
    }

    public function testSetInvalidDateTimeModificatorToOptionWillThrowException(): void
    {
        self::expectException(InvalidTimeFrameOptionValue::class);

        $timeframe = TimeFrame::create();
        $timeframe->setOption(TimeFrame::OPT_SPECTATOR_ENTRY, 'foo bar baz');
    }
}
