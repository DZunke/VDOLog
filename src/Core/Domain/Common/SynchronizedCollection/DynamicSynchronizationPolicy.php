<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\SynchronizedCollection;

use Closure;

use function call_user_func;

final class DynamicSynchronizationPolicy implements SynchronizationPolicy
{
    private Closure $addCallback;
    private Closure $updateCallback;
    private Closure $removeCallback;

    public function __construct(Closure $addCallback, Closure $updateCallback, Closure $removeCallback)
    {
        $this->addCallback    = $addCallback;
        $this->updateCallback = $updateCallback;
        $this->removeCallback = $removeCallback;
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
