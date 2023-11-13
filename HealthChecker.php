<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle;

use DateTimeImmutable;
use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use OhDear\HealthCheckResults\CheckResults;

final class HealthChecker
{
    /** @var array<CheckerInterface> */
    private array $checkers = [];

    public function addHealthChecker(CheckerInterface $checker): void
    {
        $this->checkers[] = $checker;
    }

    public function runAllChecks(): CheckResults
    {
        $checkResults = new CheckResults(
            new DateTimeImmutable()
        );

        foreach ($this->checkers as $checker) {
            $checkResults->addCheckResult($checker());
        }

        return $checkResults;
    }
}