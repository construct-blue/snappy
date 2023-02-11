<?php

namespace Blue\Core\Application\Session;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    private SessionContainer $sessionContainer;

    public function __construct(SessionContainer $clientContainer)
    {
        $this->sessionContainer = $clientContainer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookies = $request->getCookieParams();
        $id = $cookies[Session::COOKIE_NAME] ?? null;
        $session = $this->sessionContainer->get($id);

        $response = $handler->handle($request->withAttribute(Session::class, $session));
        if ($session->getId() !== $id && $session->isModified()) {
            return $response->withAddedHeader('Set-Cookie', $session->getCookie());
        }
        return $response;
    }
}
