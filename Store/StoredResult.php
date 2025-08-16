<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Store;

use DateTimeImmutable;
use OhDear\HealthCheckResults\CheckResult;
use Symfony\Component\Clock\ClockInterface;

final class StoredResult
{
    public readonly DateTimeImmutable $createdAt;

    public function __construct(
        public readonly string $identifier,
        public readonly CheckResult $checkResult,
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public function isExpired(int $frequency, int $threshold, ClockInterface $clock): bool
    {
        return ($this->createdAt->getTimestamp() + $frequency + $threshold) < $clock->now();
    }
}
