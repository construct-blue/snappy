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
        $session = $this->sessionContainer->get($cookies[Session::COOKIE_NAME] ?? null);

        $response = $handler->handle($request->withAttribute(Session::class, $session));
        if ($session->isModified() && !isset($cookies[Session::COOKIE_NAME])) {
            return $response->withAddedHeader(
                'Set-Cookie',
                Session::COOKIE_NAME . '=' . $session->getId() . '; Path=/'
            );
        }
        return $response;
    }
}
