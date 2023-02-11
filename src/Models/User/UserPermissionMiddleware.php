<?php

declare(strict_types=1);

namespace Blue\Models\User;

use Blue\Core\Application\Session\Session;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

class UserPermissionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult|null $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        $user = UserRepository::instance()->findBySession($session);
        $request = $request->withAttribute(User::class, $user);

        if ($routeResult->isSuccess()) {
            $routeOptions = $routeResult->getMatchedRoute()->getOptions();
            if (empty($routeOptions[UserPermission::class])) {
                return $handler->handle($request);
            }

            Assert::isInstanceOf(
                $routeOptions[UserPermission::class],
                UserPermission::class,
                'Invalid permission route option'
            );

            if (null === $user) {
                return $handler->handle($request->withoutAttribute(RouteResult::class));
            }

            foreach ($user->getRoles() as $role) {
                if ($role->hasPermission($routeOptions[UserPermission::class])) {
                    return $handler->handle($request);
                }
            }
        }
        return $handler->handle($request->withoutAttribute(RouteResult::class));
    }
}
