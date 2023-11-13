<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Controller;

use Devolicious\OhDearAppHealthBundle\HealthChecker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class HealthController
{
    public function __construct(
        private readonly HealthChecker $healthChecker,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $checkResults = $this->healthChecker->runAllChecks();

        return new JsonResponse(
            $checkResults->toJson(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );
    }
}