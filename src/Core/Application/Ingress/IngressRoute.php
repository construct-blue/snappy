<?php

declare(strict_types=1);

namespace Blue\Core\Application\Ingress;

use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Blue\Core\Application\AbstractSnapp;
use Blue\Core\View\DefaultVariableMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IngressRoute implements MiddlewareInterface
{
    private AbstractSnapp $application;
    private string $path;
    private ?string $domain;

    private function __construct(AbstractSnapp $application, string $path, ?string $domain)
    {
        $application->pipe(UrlHelperMiddleware::class);
        $application->pipe(ServerUrlMiddleware::class);
        $application->init();
        $this->application = $application;
        $this->path = $path;
        $this->domain = $domain;
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

    public static function app(AbstractSnapp $application, string $path, string $domain = null): IngressRoute
    {
        return new IngressRoute($application, $path, $domain);
    }

    private function matchUri(UriInterface $uri): bool
    {
        return null === $this->domain && str_starts_with($uri->getPath(), $this->path)
            || $uri->getHost() === $this->domain && str_starts_with($uri->getPath(), $this->path);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->matchUri($request->getUri())) {
            $ingressResult = new IngressResult($this->application, $this->path);
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
