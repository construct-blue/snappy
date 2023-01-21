<?php

declare(strict_types=1);

namespace Blue\Core\Http\Uri;

use Mezzio\Helper\UrlHelper;
use Psr\Http\Message\{UriFactoryInterface, UriInterface};

use function array_replace;
use function http_build_query;
use function parse_str;

class UriBuilder
{
    private UriInterface $baseUri;
    private UriInterface $uri;
    private UriInterface $currentUri;

    private UriFactoryInterface $factory;

    private UrlHelper $routePathHelper;

    final public function __construct(UriFactoryInterface $factory, UrlHelper $routePathHelper)
    {
        $this->factory = $factory;
        $this->routePathHelper = $routePathHelper;
        $this->baseUri = $this->factory->createUri();
        $this->uri = $this->factory->createUri();
        $this->currentUri = $this->factory->createUri();
    }

    public function setCurrentUri(UriInterface $uri): UriBuilder
    {
        $this->currentUri = clone $uri;
        return $this;
    }

    public function withCurrentUri(): UriBuilder
    {
        return $this->withUri($this->currentUri);
    }

    public function withUri(UriInterface $uri): UriBuilder
    {
        $clone = clone $this;
        $clone->uri = $uri;
        return $clone;
    }

    public function withParams($params): UriBuilder
    {
        $clone = clone $this;
        $clone->uri = $clone->uri->withQuery(http_build_query($params));
        return $clone;
    }

    public function withRemovedParam(string ...$params): UriBuilder
    {
        parse_str($this->uri->getQuery(), $existingParams);
        foreach ($params as $param) {
            unset($existingParams[$param]);
        }
        return $this->withParams($existingParams);
    }

    public function withAppendedParams(array $params): UriBuilder
    {
        $clone = clone $this;
        parse_str($clone->uri->getQuery(), $existingParams);
        $clone->uri = $clone->uri->withQuery(http_build_query(array_replace($existingParams, $params)));
        return $clone;
    }

    public function withPath(string $path): UriBuilder
    {
        $clone = clone $this;
        $clone->uri = $clone->uri->withPath($path);
        return $clone;
    }

    public function withHost(string $host): UriBuilder
    {
        $clone = clone $this;
        $clone->uri = $clone->uri->withHost($host);
        return $clone;
    }

    public function withScheme(string $scheme): UriBuilder
    {
        $clone = clone $this;
        $clone->uri = $clone->uri->withScheme($scheme);
        return $clone;
    }

    public function withCurrentScheme(): UriBuilder
    {
        return $this->withScheme($this->currentUri->getScheme());
    }

    public function withRoute(string $routeName, array $routeParams = []): UriBuilder
    {
        return $this->withPath($this->routePathHelper->generate($routeName, $routeParams));
    }

    public function setBasePath(string $path): UriBuilder
    {
        $this->baseUri = $this->baseUri->withPath($path);
        return $this;
    }

    public function withAppendedPath(string $path): UriBuilder
    {
        return $this->withPath(rtrim($this->uri->getPath(), '/') . $path);
    }

    public function __clone()
    {
        $this->baseUri = clone $this->baseUri;
        $this->uri = clone $this->uri;
    }

    public function merged(UriInterface $base, UriInterface $append): UriInterface
    {
        $result = $this->factory->createUri();
        if ($append->getScheme()) {
            $result = $result->withScheme($append->getScheme());
        }
        if ($append->getHost()) {
            $result = $result->withHost($append->getHost());
        }

        if ($base->getPath() === '/') {
            $result = $result->withPath($append->getPath());
        } else {
            $result = $result->withPath($base->getPath() . $append->getPath());
        }

        if ($append->getQuery()) {
            if ($base->getQuery()) {
                $query = $base->getQuery() . '&' . $append->getQuery();
            } else {
                $query = $append->getQuery();
            }
            $result = $result->withQuery($query);
        }
        return $result;
    }

    public function build(): UriInterface
    {
        return $this->merged($this->baseUri, $this->uri);
    }

    public function __toString()
    {
        return $this->build()->__toString();
    }
}
