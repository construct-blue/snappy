<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\Application\Session\Session;
use Blue\Core\Http\RequestAttribute;
use Blue\Core\Http\UriBuilder;
use Laminas\Diactoros\CallbackStream;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler implements RequestHandlerInterface, MiddlewareInterface
{
    public function __construct(private readonly TemplateRendererInterface $renderer)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->assign('requestId', RequestAttribute::REQUEST_ID->getFrom($request));
        $uriBuilder = $this->getUriBuilder($request);
        $this->assign('basePath', $uriBuilder->withPath(''));
        $session = $this->getSession($request);
        $this->assign('messages', $session->popMessages());
        $this->assign('validations', $session->popValidations());

        return $this->handle($request);
    }

    public function getRenderer(): TemplateRendererInterface
    {
        return $this->renderer;
    }

    public function render(string $name, $model = []): StreamInterface
    {
        return new CallbackStream(fn() => $this->getRenderer()->render($name, $model));
    }

    public function assign(string $name, $value)
    {
        $this->getRenderer()->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, $name, $value);
    }

    public function getUriBuilder(ServerRequestInterface $request): UriBuilder
    {
        return $this->getSnappResult($request)->getUriBuilder();
    }

    public function getSnappResult(ServerRequestInterface $request): SnappRouteResult
    {
        return $request->getAttribute(SnappRouteResult::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @return SnappRoute[]
     */
    public function getSnapps(ServerRequestInterface $request): array
    {
        return RequestAttribute::SNAPP_ROUTES->getFrom($request);
    }

    public function getSession(ServerRequestInterface $request): Session
    {
        return $request->getAttribute(Session::class);
    }
}
