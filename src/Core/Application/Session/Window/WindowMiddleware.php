<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session\Window;

use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WindowMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var null|IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
        if ($ingressResult) {
            $application = $ingressResult->getSnApp();
            /** @var Session $session */
            $session = $request->getAttribute(Session::class);
            $window = $session->openWindow($application);
            return $handler->handle($request->withAttribute(Window::class, $window))
                ->withAddedHeader('Set-Cookie', Window::COOKIE_NAME . '=' . $window->getId());
        }
        return $handler->handle($request);
    }
}
