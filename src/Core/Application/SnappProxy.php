<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @template T of SnappInterface
 */
class SnappProxy implements SnappInterface
{
    private SnappInterface $snapp;

    /**
     * @param Closure():SnappInterface $closure
     */
    public function __construct(private readonly Closure $closure)
    {
    }

    public function pipe($middlewareOrPath, $middleware = null): void
    {
        $this->resolve()->pipe($middlewareOrPath, $middleware);
    }

    public function init(): SnappInterface
    {
        return $this->resolve()->init();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->resolve()->getContainer();
    }

    /**
     * @return T
     */
    public function resolve(): SnappInterface
    {
        return $this->snapp ?? $this->snapp = ($this->closure)();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->resolve()->process($request, $handler);
    }

    public function run(): void
    {
        $this->resolve()->run();
    }
}
