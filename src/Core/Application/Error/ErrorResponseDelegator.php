<?php

namespace Blue\Core\Application\Error;

use Blue\Core\Application\Session\Session;
use Blue\Core\Application\Session\MessageType;
use Blue\Core\Exception\CoreException;
use Blue\Core\Http\Status;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorResponseDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): callable
    {
        $generator = $callback();
        return function (
            Throwable $e,
            ServerRequestInterface $request,
            ResponseInterface $response
        ) use ($generator) {
            /** @var null|Session $session */
            $session = $request->getAttribute(Session::class);

            if ($session && $e->getCode() > 511 && $e->getCode() < 600) {
                $status = Status::tryFrom($e->getCode());
                if (
                    $status === Status::VALIDATION_ERROR
                    && $e instanceof CoreException
                    && $e->getReference()
                ) {
                    $session->addValidation($e->getReference(), $e->getMessage());
                } else {
                    $session->addMessage($e->getMessage(), MessageType::ERROR);
                }
            }

            /** @var ResponseInterface $response */
            $response = $generator($e, $request, $response);
            return $response;
        };
    }
}
