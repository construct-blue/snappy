<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Blue\Core\Application\Session\Session;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ActionHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }

    public function getSession(ServerRequestInterface $request): Session
    {
        return $request->getAttribute(Session::class);
    }
}
