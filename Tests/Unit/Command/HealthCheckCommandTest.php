<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Command;

use Devolicious\OhDearAppHealthBundle\Command\HealthCheckCommand;
use Devolicious\OhDearAppHealthBundle\HealthCheckerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class HealthCheckCommandTest extends TestCase
{
    /**
     * @test
     */
    public function run_command(): void
    {
        $healthCheck = $this->createMock(HealthCheckerInterface::class);
        $healthCheck->expects($this->once())
            ->method('runAllChecksAndStore');

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $command = new HealthCheckCommand($healthCheck);
        $command->run($input, $output);
    }
}
