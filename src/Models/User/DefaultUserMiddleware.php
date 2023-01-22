<?php

declare(strict_types=1);

namespace Blue\Models\User;

use Blue\Core\Application\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DefaultUserMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly string $defaultUserName)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);

        if (null === $session->getUser()) {
            $session->setUser(UserRepository::instance()->findByName($this->defaultUserName));
        }

        return $handler->handle($request);
    }
}
