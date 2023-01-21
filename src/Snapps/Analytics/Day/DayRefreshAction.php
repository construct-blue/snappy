<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics\Day;

use Blue\Core\Analytics\DayCalculation;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DayRefreshAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        (new DayCalculation())->execute(true);
        return new Response();
    }
}