<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game\Model;

use DateTimeImmutable;
use VDOLog\Core\Application\Game\Model\AccessScanPointCsvParser\AccessScanPoint;

use function explode;
use function str_replace;
use function strlen;
use function trim;

use const PHP_EOL;

class AccessScanPointCsvParser
{
    private string $delimeter = ';';

    /** @return AccessScanPoint[] */
    public function parse(DateTimeImmutable $defaultDateTime, string $csvContent): array
    {
        $parsedEntries = [];

        $rows = explode(PHP_EOL, $csvContent);
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            // @phpstan-ignore-next-line false positive: https://phpstan.org/r/742f9409-d0f3-497d-b022-53bb02e5134d
            $columns = explode($this->delimeter, $row);
            if (strlen(trim($columns[0])) === 0) {
                continue;
            }

            // Entry consists only of a hour and minute timestamp - set correct date afterwards
            $parsedTime = explode(':', $columns[0]);

            $parsedEntries[] = new AccessScanPoint(
                $defaultDateTime->setTime((int) $parsedTime[0], (int) $parsedTime[1]),
                (int) str_replace(',', '', $columns[4]),
                (int) str_replace(',', '', $columns[3]),
            );
        }

        return $parsedEntries;
    }

    public function setDelimeter(string $delimeter): void
    {
        $this->delimeter = $delimeter;
    }
}
