<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit;

use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use Devolicious\OhDearAppHealthBundle\HealthChecker;
use Devolicious\OhDearAppHealthBundle\Store\ResultStore;
use Devolicious\OhDearAppHealthBundle\Store\StoredResult;
use OhDear\HealthCheckResults\CheckResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class HealthCheckerTest extends TestCase
{
    /** @var MockObject<ResultStore> */
    /** @phpstan-ignore-next-line */
    private MockObject $resultStore;

    /** @var MockObject<CheckerInterface> */
    /** @phpstan-ignore-next-line */
    private MockObject $checker;

    /** @phpstan-ignore-next-line */
    private HealthChecker $healthChecker;

    protected function setUp(): void
    {
        $this->resultStore = $this->createMock(ResultStore::class);
        $this->checker = $this->createMock(CheckerInterface::class);
        $this->healthChecker = new HealthChecker($this->resultStore, 60);
        $this->healthChecker->addHealthChecker($this->checker);
    }

    /**
     * @test
     */
    public function fetch_latest_check_results_with_cache(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn($storedResult);

        $this->checker
            ->expects($this->never())
            ->method('runCheck');

        $this->resultStore
            ->expects($this->never())
            ->method('save');

        $this->checker
            ->expects($this->once())
            ->method('identify');

        $this->checker
            ->expects($this->exactly(2))
            ->method('frequency')
            ->willReturn(3600);

        $results = $this->healthChecker->fetchLatestCheckResults();

        $this->assertCount(1, $results->checkResults());
        $this->assertEquals($checkResult, $results->checkResults()[0]);
    }

    /**
     * @test
     */
    public function fetch_latest_check_results_without_cache(): void
    {
        $checkResult = $this->createMock(CheckResult::class);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn(null);

        $this->checker
            ->expects($this->once())
            ->method('runCheck')
            ->willReturn($checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('save');

        $this->checker
            ->expects($this->exactly(3))
            ->method('identify');

        $this->checker
            ->expects($this->exactly(1))
            ->method('frequency')
            ->willReturn(3600);

        $results = $this->healthChecker->fetchLatestCheckResults();

        $this->assertCount(1, $results->checkResults());
        $this->assertEquals($checkResult, $results->checkResults()[0]);
    }

    /**
     * @test
     */
    public function fetch_latest_check_results_with_zero_frequency(): void
    {
        $checkResult = $this->createMock(CheckResult::class);

        $this->resultStore
            ->expects($this->never())
            ->method('fetchLastResult');

        $this->checker
            ->expects($this->once())
            ->method('runCheck')
            ->willReturn($checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('save');

        $this->checker
            ->expects($this->exactly(2))
            ->method('identify');

        $this->checker
            ->expects($this->exactly(1))
            ->method('frequency')
            ->willReturn(0);

        $results = $this->healthChecker->fetchLatestCheckResults();

        $this->assertCount(1, $results->checkResults());
        $this->assertEquals($checkResult, $results->checkResults()[0]);
    }

    /**
     * @test
     */
    public function fetch_latest_check_results_with_expired_cache(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $checkResult->meta = [];

        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn($storedResult);

        $this->checker
            ->expects($this->never())
            ->method('runCheck');

        $this->resultStore
            ->expects($this->never())
            ->method('save');

        $this->checker
            ->expects($this->exactly(1))
            ->method('identify');

        $this->checker
            ->expects($this->exactly(3))
            ->method('frequency')
            ->willReturnOnConsecutiveCalls(3600, -1, 3600);

        $results = $this->healthChecker->fetchLatestCheckResults();

        $this->assertCount(1, $results->checkResults());
        $this->assertEquals($checkResult, $results->checkResults()[0]);
    }

    /**
     * @test
     */
    public function run_all_checks(): void
    {
        $checkResult = $this->createMock(CheckResult::class);

        $this->checker
            ->expects($this->once())
            ->method('runCheck')
            ->willReturn($checkResult);

        $results = $this->healthChecker->runAllChecks();

        $this->assertCount(1, $results->checkResults());
        $this->assertEquals($checkResult, $results->checkResults()[0]);
    }

    /**
     * @test
     */
    public function run_all_checks_and_store_with_cache_not_expired(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn($storedResult);

        $this->checker
            ->expects($this->exactly(1))
            ->method('frequency')
            ->willReturn(3600);

        $this->checker
            ->expects($this->never())
            ->method('runCheck');

        $this->healthChecker->runAllChecksAndStore();
    }

    /**
     * @test
     */
    public function run_all_checks_and_store_with_cache_expired(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn($storedResult);

        $this->checker
            ->expects($this->exactly(1))
            ->method('frequency')
            ->willReturn(-1);

        $this->checker
            ->expects($this->once())
            ->method('runCheck')
            ->willReturn($checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('save');

        $this->healthChecker->runAllChecksAndStore();
    }

    /**
     * @test
     */
    public function run_all_checks_and_store_with_no_cache(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn(null);

        $this->checker
            ->expects($this->never())
            ->method('frequency');

        $this->checker
            ->expects($this->once())
            ->method('runCheck')
            ->willReturn($checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('save');

        $this->healthChecker->runAllChecksAndStore();
    }

    /**
     * @test
     */
    public function run_all_checks_and_store_omit_cache(): void
    {
        $checkResult = $this->createMock(CheckResult::class);
        $storedResult = new StoredResult('identifier', $checkResult);

        $this->resultStore
            ->expects($this->once())
            ->method('fetchLastResult')
            ->willReturn($storedResult);

        $this->checker
            ->expects($this->never())
            ->method('frequency');

        $this->checker
            ->expects($this->once())
            ->method('runCheck');

        $this->resultStore
            ->expects($this->once())
            ->method('save');

        $this->healthChecker->runAllChecksAndStore(true);
    }
}
