<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\Application\Ingress\IngressRoute;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler extends \Blue\Core\Application\Handler\TemplateHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $snappCode = $request->getAttribute('snapp');

        if (!$snappCode) {
            $uriBuilder = $this->getUriBuilder($request);
            $siteSnapps = array_filter($this->getSnapps($request), fn(IngressRoute $route) => $route->isSite());
            $firstSnapp = reset($siteSnapps);
            if (!$firstSnapp) {
                return $handler->handle($request);
            }
            return new RedirectResponse(
                (string)$uriBuilder
                    ->withCurrentUri()
                    ->withAppendedPath("/{$firstSnapp->getCode()}")
            );
        }

        foreach ($this->getSnapps($request) as $snapp) {
            if ($snapp->getCode() === $snappCode) {
                $this->assign('snapp', $snapp);
            }
        }

        return $this->handle($request);
    }
}
