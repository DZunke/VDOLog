<?php

declare(strict_types=1);

namespace VDOLog\Web\Model\Chart;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use VDOLog\Core\Domain\Game;

use function count;
use function max;

class GameExits
{
    private function __construct()
    {
    }

    public static function fromGame(ChartBuilderInterface $chartBuilder, Game $game): Chart
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $labels = [];
        $data   = [];
        $sum    = 0;

        foreach ($game->getAccessScanPoints() as $accessScanPoint) {
            if ($game->getTimeFrame()->getEventStartsAt() > $accessScanPoint->getTime()) {
                continue;
            }

            $labels[] = $accessScanPoint->getTime()->format('H:i');
            $data[]   = $accessScanPoint->getExits();
            $sum     += $accessScanPoint->getExits();
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'borderColor' => '#0058a3',
                    'pointBackgroundColor' => '#0058a3',
                    'pointBorderColor' => '#0058a3',
                    'data' => $data,
                    'fill' => false,
                    'lineTension' => 0.1,
                    'pointRadius' => 4,
                ],
            ],
        ]);

        $maxValue = count($data) > 0 ? max($data) : 0;
        $chart->setOptions([
            'aspectRatio' => 3.5,
            'legend' => ['display' => false],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => ['min' => 0, 'max' => $maxValue + ($maxValue / 10)],
                        'display' => false,
                        'gridLines' => ['display' => false],
                    ],
                ],
                'xAxes' => [['gridLines' => ['display' => false]]],
            ],
        ]);

        return $chart;
    }
}
