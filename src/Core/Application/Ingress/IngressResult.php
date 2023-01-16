<?php

namespace Blue\Core\Application\Ingress;

use Blue\Core\Application\AbstractSnapp;
use Blue\Core\Http\Uri\UriBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IngressResult implements MiddlewareInterface
{
    public function __construct(private AbstractSnapp $application, private string $path)
    {
    }

    /**
     * @return AbstractSnapp
     */
    public function getApplication(): AbstractSnapp
    {
        return $this->application;
    }

    public function getUriBuilder(): UriBuilder
    {
        return $this->getApplication()->getContainer()->get(UriBuilder::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->prepareRequestWithTruncatedPrefix($request, $this->path);

        $this->getUriBuilder()->setBasePath($this->path);
        $this->getUriBuilder()->setCurrentUri($request->getUri());

        return $this->application->process($request, $handler);
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
