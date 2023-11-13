<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle;

use DateTimeImmutable;
use Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface;
use Devolicious\OhDearAppHealthBundle\Store\ResultStore;
use Devolicious\OhDearAppHealthBundle\Store\StoredResult;
use OhDear\HealthCheckResults\CheckResults;

final class HealthChecker
{
    /** @var array<CheckerInterface> */
    private array $checkers = [];

    public function __construct(
        private readonly ResultStore $resultStore
    ) {
    }

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
            $checkResults->addCheckResult($checker->runCheck());
        }

        return $checkResults;
    }

    public function runAllChecksAndStore(): void
    {
        foreach ($this->checkers as $checker) {
            $lastResult = $this->resultStore->fetchLastResult($checker->identify());

            if (null !== $lastResult && false === $lastResult->isExpired($checker->frequency())) {
                continue;
            }

            $result = $checker->runCheck();

            $this->resultStore->save(
                $checker->identify(),
                new StoredResult(
                    $checker->identify(),
                    $result
                )
            );
        }
    }
}
