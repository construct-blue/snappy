<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\Analytics\AnalyticsMiddleware;
use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Session\Session;
use Blue\Core\Application\Session\Window\Window;
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
            /** @var Window $window */
            $window = $request->getAttribute(Window::class);
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'window',
                $window
            );

            /** @var IngressResult $ingressResult */
            $ingressResult = $request->getAttribute(IngressResult::class);
            $uriBuilder = $ingressResult->getUriBuilder();

            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'basePath',
                $uriBuilder->withPath('/')
            );
        }
        return $handler->handle($request);
    }
}
