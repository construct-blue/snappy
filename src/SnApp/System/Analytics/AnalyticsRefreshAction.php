<?php

declare(strict_types=1);

namespace Blue\SnApp\System\Analytics;

use Laminas\Diactoros\Response;
use Blue\Core\Analytics\DayCalculation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AnalyticsRefreshAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        (new DayCalculation())->execute(true);
        return new Response();
    }
}
