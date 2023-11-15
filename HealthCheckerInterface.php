<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle;

use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use OhDear\HealthCheckResults\CheckResults;

interface HealthCheckerInterface
{
    public function addHealthChecker(CheckerInterface $checker): void;

    public function fetchLatestCheckResults(): CheckResults;

    public function runAllChecks(): CheckResults;

    public function runAllChecksAndStore(): void;
}
