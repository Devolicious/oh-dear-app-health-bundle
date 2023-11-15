<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Command;

use Devolicious\OhDearAppHealthBundle\HealthChecker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'health:check',
    description: 'Run all health checks',
)]
final class HealthCheckCommand extends Command
{
    public function __construct(
        private readonly HealthChecker $healthChecker,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->healthChecker->runAllChecksAndStore();

        return Command::SUCCESS;
    }
}
