<?php

declare(strict_types=1);

namespace Blue\Core\Application\Ingress;

use Blue\Core\Application\SnappCode;
use Blue\Core\Application\SnappInterface;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
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
    private ?string $code = null;
    private ?string $name = null;
    private ?string $domainSuffix = null;
    private array $domainAliases = [];
    private bool $cms = true;

    public function __construct(SnappInterface $snapp, string $path, ?string $domain = null)
    {
        $this->snapp = $snapp;
        $this->path = $path;
        $this->domain = $domain;
    }

    public function build(): void
    {
        $this->snapp->init();
        /** @var RouterInterface $router */
        $router = $this->snapp->getContainer()->get(RouterInterface::class);
        $router->match(new ServerRequest());
    }

    public function setDomainSuffix(?string $domainSuffix): void
    {
        $this->domainSuffix = $domainSuffix;
    }

    public function addAlias(string $domainAlias): self
    {
        $this->domainAliases[] = $domainAlias;
        return $this;
    }

    public function setCode(?string $code): IngressRoute
    {
        $this->code = $code;
        return $this;
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
        if ($this->domainSuffix) {
            return $this->domain . $this->domainSuffix;
        }
        return $this->domain;
    }


    public function getDomainAliases(): array
    {
        if ($this->domainSuffix) {
            $result = [];
            foreach ($this->domainAliases as $domainAlias) {
                $result[] = $domainAlias . $this->domainSuffix;
            }
            return $result;
        }
        return $this->domainAliases;
    }

    public function getUri(): UriInterface
    {
        $schema = '';
        if ($this->getDomain()) {
            $schema = '//';
        }
        return new Uri($schema . $this->getDomain() . $this->getPath());
    }

    public function getCode(): string
    {
        return $this->code ?? SnappCode::build($this->domain, $this->path);
    }

    public function getName(): ?string
    {
        return $this->name ?? ltrim((string)$this->getUri(), '/');
    }

    public function setName(?string $name): IngressRoute
    {
        $this->name = $name;
        return $this;
    }

    public function isCms(): bool
    {
        return $this->cms;
    }

    public function setCms(bool $cms): IngressRoute
    {
        $this->cms = $cms;
        return $this;
    }

    private function matchUri(UriInterface $uri): bool
    {
        $domains = $this->getDomainAliases();
        $domains[] = $this->getDomain();
        return null === $this->getDomain() && str_starts_with($uri->getPath(), $this->path)
            || in_array($uri->getHost(), $domains, true) && str_starts_with($uri->getPath(), $this->path);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->matchUri($request->getUri())) {
            $ingressResult = new IngressResult($this->snapp, $this->path, $this);
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
