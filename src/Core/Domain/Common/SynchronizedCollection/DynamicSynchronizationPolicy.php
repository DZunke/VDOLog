<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\SynchronizedCollection;

use Closure;

use function call_user_func;

final class DynamicSynchronizationPolicy implements SynchronizationPolicy
{
    public function __construct(private Closure $addCallback, private Closure $updateCallback, private Closure $removeCallback)
    {
    }

    public function handleAdd(mixed $newData): mixed
    {
        return call_user_func($this->addCallback, $newData);
    }

    public function handleUpdate(mixed $origin, mixed $updatedData): mixed
    {
        return call_user_func($this->updateCallback, $origin, $updatedData);
    }

    public function handleRemove(mixed $origin): void
    {
        call_user_func($this->removeCallback, $origin);
    }
}
