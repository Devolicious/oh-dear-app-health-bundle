<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Tests\Unit\Controller;

use Devolicious\OhDearAppHealthBundle\Controller\HealthController;
use Devolicious\OhDearAppHealthBundle\HealthCheckerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HealthControllerTest extends TestCase
{
    private const SECRET = 'TopSecret';

    /** @var MockObject<HealthCheckerInterface> */
    /** @phpstan-ignore-next-line */
    private MockObject $healthChecker;

    /** @phpstan-ignore-next-line */
    private HealthController $controller;

    protected function setUp(): void
    {
        $this->healthChecker = $this->createMock(HealthCheckerInterface::class);
        $this->controller = new HealthController(
            $this->healthChecker,
            self::SECRET,
        );
    }

    /**
     * @test
     */
    public function run_controller_without_correct_secret_header(): void
    {
        $headerBag = $this->createMock(HeaderBag::class);
        $headerBag
            ->expects($this->once())
            ->method('get')
            ->with(HealthController::OH_DEAR_HEADER)
            ->willReturn('WrongSecret');

        $request = $this->createMock(Request::class);
        $request->headers = $headerBag;

        $response = ($this->controller)($request);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function run_controller_with_correct_secret_header(): void
    {
        $headerBag = $this->createMock(HeaderBag::class);
        $headerBag
            ->expects($this->once())
            ->method('get')
            ->with(HealthController::OH_DEAR_HEADER)
            ->willReturn(self::SECRET);

        $request = $this->createMock(Request::class);
        $request->headers = $headerBag;

        $this->healthChecker
            ->expects($this->once())
            ->method('fetchLatestCheckResults');

        $response = ($this->controller)($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
