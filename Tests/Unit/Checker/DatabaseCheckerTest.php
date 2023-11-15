<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Checker;

use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use Devolicious\OhDearAppHealthBundle\Checker\DatabaseChecker;
use OhDear\HealthCheckResults\CheckResult;
use PHPUnit\Framework\TestCase;

final class DatabaseCheckerTest extends TestCase
{
    /** @phpstan-ignore-next-line */
    private CheckerInterface $checker;

    protected function setUp(): void
    {
        //        $this->checker = new DatabaseChecker($this->createMock(EntityManagerInterface::class));

        parent::setUp();
    }

    /**
     * @test
     */
    public function frequency_should_be(): void
    {
        $this->markTestSkipped('Not implemented yet');
        //        $this->assertEquals(60, $this->checker->frequency());
    }

    /**
     * @test
     */
    public function run_check_success(): void
    {
        $this->markTestSkipped('Not implemented yet');

        //        $result = $this->checker->runCheck();
        //        $this->assertEquals($this->checker->identify(), $result->name);
        //        $this->assertEquals(CheckResult::STATUS_OK, $result->status);
    }

    /**
     * @test
     */
    public function run_check_failed(): void
    {
        $this->markTestSkipped('Not implemented yet');

        //        $result = $this->checker->runCheck();
        //        $this->assertEquals($this->checker->identify(), $result->name);
        //        $this->assertEquals(CheckResult::STATUS_FAILED, $result->status);
    }
}
