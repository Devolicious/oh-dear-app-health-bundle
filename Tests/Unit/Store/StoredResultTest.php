<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Store;

use Devolicious\OhDearAppHealthBundle\Store\StoredResult;
use OhDear\HealthCheckResults\CheckResult;
use PHPUnit\Framework\TestCase;

final class StoredResultTest extends TestCase
{
    private const THRESHOLD = 60;
    /**
     * @test
     */
    public function result_is_fresh(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $result = new StoredResult('identifier', $checkResult);
        $this->assertFalse($result->isExpired(60, self::THRESHOLD));
    }

    /**
     * @test
     */
    public function result_is_expired_but_falls_into_threshold(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $result = new StoredResult('identifier', $checkResult);
        $this->assertFalse($result->isExpired(-1, self::THRESHOLD));
    }

    /**
     * @test
     */
    public function result_is_expired(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $result = new StoredResult('identifier', $checkResult);
        $this->assertTrue($result->isExpired(-61, self::THRESHOLD));
    }
}
