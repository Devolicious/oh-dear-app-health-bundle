<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Checker;

use OhDear\HealthCheckResults\CheckResult;

interface CheckerInterface
{
    public function __invoke(): CheckResult;
}
