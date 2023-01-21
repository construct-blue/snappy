<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\Session\Session;
use Blue\Core\Http\Attribute;
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

    public function getSession(ServerRequestInterface $request): Session
    {
        return $request->getAttribute(Session::class);
    }

    public function assignSnapps(ServerRequestInterface $request)
    {
        $snapps = [];
        /** @var IngressRoute $route */
        foreach (Attribute::SNAPP_ROUTES->getFrom($request) as $route) {
            if ($route->isCms()) {
                $snapps[$route->getCode()] = $route->getName();
            }
        }
        $this->assign('snapps', $snapps);
    }
}
