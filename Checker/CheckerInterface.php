<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Checker;

use OhDear\HealthCheckResults\CheckResult;

interface CheckerInterface
{
    public function runCheck(): CheckResult;

    public function identify(): string;

    /**
     * How often should this check be run in seconds
     *
     * @return int
     */
    public function frequency(): int;
}
