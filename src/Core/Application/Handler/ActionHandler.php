<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Blue\Core\Application\Session\Session;
use Blue\Core\Application\Session\MessageType;
use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Core\Exception\CoreException;
use Blue\Core\Http\Status;
use Blue\Core\Http\UriBuilder;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ActionHandler implements RequestHandlerInterface, MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $this->handle($request);
            $this->getSession($request)->addMessage('Success', MessageType::SUCCESS);
        } catch (CoreException $exception) {
            if (in_array(Status::tryFrom($exception->getCode()), [Status::VALIDATION_ERROR, Status::GENERAL_ERROR])) {
                if ($exception->getReference()) {
                    $this->getSession($request)->addValidation($exception->getReference(), $exception->getMessage());
                } else {
                    $this->getSession($request)->addMessage($exception->getMessage(), MessageType::ERROR);
                }
            } else {
                throw $exception;
            }
        }
        return $response ?? new Response();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws CoreException
     */
    abstract public function handle(ServerRequestInterface $request): ResponseInterface;

    public function getSession(ServerRequestInterface $request): Session
    {
        return $request->getAttribute(Session::class);
    }

    public function getUriBuilder(ServerRequestInterface $request): UriBuilder
    {
        return $this->getSnappResult($request)->getUriBuilder();
    }

    public function getSnappResult(ServerRequestInterface $request): SnappRouteResult
    {
        return $request->getAttribute(SnappRouteResult::class);
    }
}
