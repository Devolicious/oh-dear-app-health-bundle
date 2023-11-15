<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Store;

use Devolicious\OhDearAppHealthBundle\Store\CachePoolStore;
use Devolicious\OhDearAppHealthBundle\Store\StoredResult;
use OhDear\HealthCheckResults\CheckResult;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CachePoolStoreTest extends TestCase
{
    private CacheItemPoolInterface $cache;
    private CachePoolStore $store;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->store = new CachePoolStore($this->cache);
    }

    /**
     * @test
     */
    public function save_result(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('save');

        $cacheItem = $this->createMock(ItemInterface::class);
        $cacheItem
            ->expects($this->once())
            ->method('set')
            ->willReturn($cacheItem);

        $this->cache
            ->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItem);

        $this->store->save('identifier', new StoredResult('identifier', $this->createMock(CheckResult::class)));
    }

    /**
     * @test
     */
    public function fetch_latest_with_hit(): void
    {
        $expectedResult = new StoredResult('identifier', $this->createMock(CheckResult::class));
        $cacheItem = $this->createMock(ItemInterface::class);
        $cacheItem
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $cacheItem
            ->expects($this->once())
            ->method('get')
            ->willReturn($expectedResult);

        $this->cache
            ->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItem);

        $actual = $this->store->fetchLastResult('identifier');

        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function fetch_latest_with_no_hit(): void
    {
        $cacheItem = $this->createMock(ItemInterface::class);
        $cacheItem
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $cacheItem
            ->expects($this->never())
            ->method('get');

        $this->cache
            ->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItem);

        $actual = $this->store->fetchLastResult('identifier');

        $this->assertNull($actual);
    }
}
