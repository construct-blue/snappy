<?php

namespace Blue\Core\Application\Error;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorResponseDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback)
    {
        $generator = $callback();
        return function (
            Throwable $e,
            ServerRequestInterface $request,
            ResponseInterface $response
        ) use ($generator) {
            /** @var ResponseInterface $response */
            $response = $generator($e, $request, $response);
            if ($e->getCode() > 511 && $e->getCode() < 600) {
                return $response->withStatus($response->getStatusCode(), $e->getMessage());
            }
            return $response;
        };
    }
}
