<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Checker;

use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use Devolicious\OhDearAppHealthBundle\Checker\DoctrineConnectionChecker;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OhDear\HealthCheckResults\CheckResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DatabaseCheckerTest extends TestCase
{
    /** @var MockObject<EntityManagerInterface> */
    /** @phpstan-ignore-next-line */
    private MockObject $entityManager;

    /** @phpstan-ignore-next-line */
    private CheckerInterface $checker;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->checker = new DoctrineConnectionChecker($this->entityManager);

        parent::setUp();
    }

    /**
     * @test
     */
    public function frequency_should_be(): void
    {
        $this->assertEquals(0, $this->checker->frequency());
    }

    /**
     * @test
     */
    public function run_check_success(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('connect')
            ->willReturn(true);

        $connection
            ->expects($this->once())
            ->method('isConnected')
            ->willReturn(true);

        $this->entityManager
            ->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturn($connection);

        $result = $this->checker->runCheck();
        $this->assertEquals($this->checker->identify(), $result->name);
        $this->assertEquals(CheckResult::STATUS_OK, $result->status);
    }

    /**
     * @test
     */
    public function run_check_failed_not_connected(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('connect')
            ->willReturn(true);

        $connection
            ->expects($this->once())
            ->method('isConnected')
            ->willReturn(false);

        $this->entityManager
            ->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturn($connection);

        $result = $this->checker->runCheck();
        $this->assertEquals($this->checker->identify(), $result->name);
        $this->assertEquals(CheckResult::STATUS_FAILED, $result->status);
    }

    /**
     * @test
     */
    public function run_check_failed_with_exception(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('connect')
            ->willThrowException(new Exception());

        $this->entityManager
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);

        $result = $this->checker->runCheck();
        $this->assertEquals($this->checker->identify(), $result->name);
        $this->assertEquals(CheckResult::STATUS_FAILED, $result->status);
    }
}
