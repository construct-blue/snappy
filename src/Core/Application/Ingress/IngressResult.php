<?php

namespace Blue\Core\Application\Ingress;

use Blue\Core\Application\SnappInterface;
use Blue\Core\Http\Uri\UriBuilder;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IngressResult implements MiddlewareInterface
{
    public function __construct(
        private readonly SnappInterface $snapp,
        private readonly string $path,
        private readonly IngressRoute $route
    ) {
    }

    /**
     * @return SnappInterface
     */
    public function getSnApp(): SnappInterface
    {
        return $this->snapp;
    }

    public function getRoute(): IngressRoute
    {
        return $this->route;
    }

    public function getUriBuilder(): UriBuilder
    {
        return $this->getSnApp()->getContainer()->get(UriBuilder::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->getSnApp()->pipe(UrlHelperMiddleware::class);
        $this->getSnApp()->pipe(ServerUrlMiddleware::class);
        $this->getSnApp()->init();

        $request = $this->prepareRequestWithTruncatedPrefix($request, $this->path);

        $this->getUriBuilder()->setBasePath($this->path);
        $this->getUriBuilder()->setCurrentUri($request->getUri());

        return $this->getSnApp()->process($request, $handler);
    }


    private function prepareRequestWithTruncatedPrefix(
        ServerRequestInterface $request,
        string $path
    ): ServerRequestInterface {
        if ($path === '/') {
            return $request;
        }
        $uri = $request->getUri();
        $path = $this->getTruncatedPath($path, $uri->getPath());
        if ($path === '') {
            $path = '/';
        }
        $new = $uri->withPath($path);
        return $request->withUri($new)->withRequestTarget($request->getRequestTarget());
    }

    private function getTruncatedPath(string $segment, string $path): string
    {
        if ($segment === $path) {
            // Decorated path and current path are the same; return empty string
            return '';
        }

        // Strip decorated path from start of current path
        return substr($path, strlen($segment));
    }
}
