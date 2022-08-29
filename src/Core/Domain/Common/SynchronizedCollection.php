<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common;

use Closure;
use VDOLog\Core\Domain\Common\SynchronizedCollection\AmbiguousElement;
use VDOLog\Core\Domain\Common\SynchronizedCollection\SynchronizationPolicy;

use function array_filter;
use function array_key_first;
use function array_values;
use function array_walk;
use function count;
use function reset;

class SynchronizedCollection
{
    /** @param mixed[] $collection */
    public function __construct(private array $collection, private SynchronizationPolicy $policy)
    {
    }

    /**
     * @param mixed[] $elements Array of elements to synchronize
     * @param Closure $matcher  Function that match element from collection to coresponding element
     *
     * @return $this
     */
    public function sync(array $elements, Closure $matcher): SynchronizedCollection
    {
        $copiedCollection = $this->collection;

        foreach ($this->collection as $key => $origin) {
            // find updated version of origin in provided array
            $updatedVersion = array_filter($elements, static fn ($element) => $matcher($element, $origin));

            // if origin is not found, then handle removal
            if (count($updatedVersion) === 0) {
                $this->policy->handleRemove($origin);

                unset($copiedCollection[$key]);
                continue;
            }

            // if origin is found then handle update
            if (count($updatedVersion) === 1) {
                $index = array_key_first($updatedVersion);

                $copiedCollection[$key] = $this->policy->handleUpdate($origin, reset($updatedVersion));

                unset($elements[$index]);
                continue;
            }

            // if origin is matched against more than one element, then throw exception
            throw new AmbiguousElement('Provided array contains ambiguous element.');
        }

        array_walk($elements, function ($addData) use (&$copiedCollection): void {
            $copiedCollection[] = $this->policy->handleAdd($addData);
        });

        return new $this(array_values($copiedCollection), $this->policy);
    }

    /** @return mixed[] */
    public function toArray(): array
    {
        return $this->collection;
    }
}
