<?php

declare(strict_types=1);

namespace Blue\Core\Application\Ingress;

use Blue\Core\Application\SnappInterface;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IngressRoute implements MiddlewareInterface
{
    private SnappInterface $snapp;
    private string $path;
    private ?string $domain;

    private function __construct(SnappInterface $snapp, string $path, ?string $domain)
    {
        $this->snapp = $snapp;
        $this->path = $path;
        $this->domain = $domain;
    }

    public function build(): void
    {
        $this->snapp->init();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public static function app(SnappInterface $snapp, string $path, string $domain = null): IngressRoute
    {
        return new IngressRoute($snapp, $path, $domain);
    }

    private function matchUri(UriInterface $uri): bool
    {
        return null === $this->domain && str_starts_with($uri->getPath(), $this->path)
            || $uri->getHost() === $this->domain && str_starts_with($uri->getPath(), $this->path);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->matchUri($request->getUri())) {
            $ingressResult = new IngressResult($this->snapp, $this->path);
            $route = new Route($this->path, $ingressResult);
            $routeResult = RouteResult::fromRoute($route);
            return $handler->handle(
                $request->withAttribute(RouteResult::class, $routeResult)
                    ->withAttribute(IngressResult::class, $ingressResult)
            );
        }
        return $handler->handle($request);
    }
}
