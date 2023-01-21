<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\Session\Session;
use Blue\Core\Http\Attribute;
use Blue\Core\Http\Uri\UriBuilder;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function getRenderer(): TemplateRendererInterface
    {
        return $this->renderer;
    }

    public function render(string $name, $params = []): string
    {
        return $this->getRenderer()->render($name, $params);
    }

    public function assign(string $name, $value)
    {
        $this->getRenderer()->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, $name, $value);
    }

    public function getUriBuilder(ServerRequestInterface $request): UriBuilder
    {
        return $this->getIngressResult($request)->getUriBuilder();
    }

    public function getIngressResult(ServerRequestInterface $request): IngressResult
    {
        return $request->getAttribute(IngressResult::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @return IngressRoute[]
     */
    public function getSnapps(ServerRequestInterface $request): array
    {
        return Attribute::SNAPP_ROUTES->getFrom($request);
    }

    public function getSession(ServerRequestInterface $request): Session
    {
        return $request->getAttribute(Session::class);
    }
}
