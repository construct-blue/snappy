<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Http\Header;
use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Session\Session;
use Blue\Core\Http\Attribute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DefaultVariableMiddleware implements MiddlewareInterface
{
    private ?TemplateRendererInterface $renderer;

    public function __construct(TemplateRendererInterface $renderer = null)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (isset($this->renderer)) {
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'backPath',
                Header::REFERER->getFrom($request, '/')
            );

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'requestId',
                Attribute::REQUEST_ID->getFrom($request)
            );

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'requestTimestamp',
                Attribute::REQUEST_TIMESTAMP->getFrom($request)
            );

            /** @var Session $session */
            $session = $request->getAttribute(Session::class);
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'session',
                $session
            );

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'language',
                $session->getLanguage()->value
            );

            /** @var IngressResult $ingressResult */
            $ingressResult = $request->getAttribute(IngressResult::class);
            $uriBuilder = $ingressResult->getUriBuilder();

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'basePath',
                $uriBuilder->withPath('')
            );

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'activeSnapp',
                $ingressResult->getRoute(),
            );

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'activePath',
                $request->getUri()->getPath(),
            );

            /** @var IngressRoute[] $snapps */
            $snapps = Attribute::SNAPP_ROUTES->getFrom($request);
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'siteSnapps',
                array_filter($snapps, fn(IngressRoute $route) => $route->isSite())
            );
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'systemSnapps',
                array_filter($snapps, fn(IngressRoute $route) => !$route->isSite())
            );
        }
        return $handler->handle($request);
    }
}
