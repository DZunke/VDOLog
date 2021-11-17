<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Domain\Common;

use PHPUnit\Framework\TestCase;
use VDOLog\Core\Domain\Common\SynchronizedCollection;
use VDOLog\Core\Domain\Common\SynchronizedCollection\SynchronizationPolicy;

use function array_merge;

class SynchronizedCollectionTest extends TestCase
{
    public function testShouldReturnEntryCollectionIfAnotherCollectionIsTheSame(): void
    {
        $entry = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::never())->method('handleAdd');
        $mockedPolicy->expects(self::never())->method('handleRemove');
        $mockedPolicy->expects(self::exactly(2))->method('handleUpdate')->willReturnCallback(
            static fn ($origin, $updateData) => array_merge($origin, $updateData)
        );

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        $synchronized = $collection->sync([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ], $matcher);

        self::assertEquals([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ], $synchronized->toArray());
    }

    public function testShouldAddNewElementToSynchronizedCollection(): void
    {
        $entry = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => isset($prop['id']) && $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::once())->method('handleAdd')->willReturnCallback(
            static fn ($newData) => array_merge(['id' => 3], $newData)
        );
        $mockedPolicy->expects(self::never())->method('handleRemove');
        $mockedPolicy->expects(self::exactly(2))->method('handleUpdate')->willReturnCallback(
            static fn ($origin, $updateData) => array_merge($origin, $updateData)
        );

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        $synchronized = $collection->sync([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
            ['name' => 'Marry'],
        ], $matcher);

        self::assertEquals([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
            ['id' => 3, 'name' => 'Marry'],
        ], $synchronized->toArray());
    }

    public function testShouldRemoveExistingElementFromSynchronizedCollection(): void
    {
        $entry = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => isset($prop['id']) && $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::never())->method('handleAdd');
        $mockedPolicy->expects(self::once())->method('handleRemove');
        $mockedPolicy->expects(self::exactly(1))->method('handleUpdate')->willReturnCallback(
            static fn ($origin, $updateData) => array_merge($origin, $updateData)
        );

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        $synchronized = $collection->sync([['id' => 2, 'name' => 'Wane']], $matcher);

        self::assertEquals([['id' => 2, 'name' => 'Wane']], $synchronized->toArray());
    }

    public function testShouldAddNewElementAndRemoveExistingElementFromSynchronizedCollection(): void
    {
        $entry = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => isset($prop['id']) && $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::once())->method('handleAdd')->willReturnCallback(static fn ($newData) => array_merge(['id' => 3], $newData));
        $mockedPolicy->expects(self::once())->method('handleRemove');
        $mockedPolicy->expects(self::exactly(1))->method('handleUpdate')->willReturnCallback(
            static fn ($origin, $updateData) => array_merge($origin, $updateData)
        );

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        $synchronized = $collection->sync([
            ['name' => 'Tim'],
            ['id' => 2, 'name' => 'Wane'],
        ], $matcher);

        self::assertEquals([
            ['id' => 2, 'name' => 'Wane'],
            ['id' => 3, 'name' => 'Tim'],
        ], $synchronized->toArray());
    }

    public function testShouldUpdateElementAndAddNewElementAndRemoveExistingElementFromSynchronizedCollection(): void
    {
        $entry = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => isset($prop['id']) && $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::once())->method('handleAdd')->willReturnCallback(static fn ($newData) => array_merge(['id' => 3], $newData));
        $mockedPolicy->expects(self::once())->method('handleRemove');
        $mockedPolicy->expects(self::exactly(1))->method('handleUpdate')->willReturnCallback(
            static fn ($origin, $updateData) => array_merge($origin, $updateData)
        );

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        $synchronized = $collection->sync([
            ['name' => 'Tim'],
            ['id' => 2, 'name' => 'Wane_UPDATED'],
        ], $matcher);

        self::assertEquals([
            ['id' => 2, 'name' => 'Wane_UPDATED'],
            ['id' => 3, 'name' => 'Tim'],
        ], $synchronized->toArray());
    }

    public function testShouldThrowExceptionIfProvidedArrayContainsAmbiguousElement(): void
    {
        $entry = [
            ['id' => 2, 'name' => 'Wane'],
        ];

        $matcher = static fn ($prop, $origin) => isset($prop['id']) && $prop['id'] === $origin['id'];

        $mockedPolicy = self::createMock(SynchronizationPolicy::class);
        $mockedPolicy->expects(self::never())->method('handleAdd');
        $mockedPolicy->expects(self::never())->method('handleRemove');
        $mockedPolicy->expects(self::never())->method('handleUpdate');

        $collection = new SynchronizedCollection($entry, $mockedPolicy);

        self::expectException(SynchronizedCollection\AmbiguousElement::class);

        $collection->sync([
            ['id' => 2, 'name' => 'Wane'],
            ['id' => 2, 'name' => 'Tim'],
        ], $matcher);
    }
}
