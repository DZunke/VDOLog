<?php

declare(strict_types=1);

namespace VDOLog\Web\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class SimpleExtensions extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('yes_no', [$this, 'formatBoolean']),
        ];
    }

    public function formatBoolean(?bool $value): string
    {
        return (bool) $value === false ? 'Nein' : 'Ja';
    }
}
