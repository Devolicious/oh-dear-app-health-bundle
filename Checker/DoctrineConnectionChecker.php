<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Checker;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OhDear\HealthCheckResults\CheckResult;
use Throwable;

final class DoctrineConnectionChecker implements CheckerInterface
{
    private const IDENTIFIER = 'Database';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        public readonly string $identifier = self::IDENTIFIER,
    ) {
    }

    public function identify(): string
    {
        return $this->identifier;
    }

    public function frequency(): int
    {
        return 0;
    }

    public function runCheck(): CheckResult
    {
        $result = new CheckResult(
            name: $this->identifier,
            label: 'Database connection status',
            shortSummary: 'connected',
            status: CheckResult::STATUS_OK,
        );

        try {
            $this->entityManager->getConnection()->connect();
            if (false === $this->entityManager->getConnection()->isConnected()) {
                throw new Exception('Database connection is not working');
            }
        } catch (Throwable) {
            $result->status = CheckResult::STATUS_FAILED;
            $result->shortSummary = 'not connected';
            $result->notificationMessage = 'Database connection is not working';
        }

        return $result;
    }
}
