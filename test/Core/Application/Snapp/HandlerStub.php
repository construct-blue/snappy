<?php

namespace BlueTest\Core\Application\Snapp;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HandlerStub implements RequestHandlerInterface
{
    public ServerRequestInterface $request;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return new EmptyResponse(404);
    }
}