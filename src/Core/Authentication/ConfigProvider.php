<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\Application\Error\NotFound\NotFound;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
                    AuthorizationMiddleware::class => AuthorizationMiddlewareFactory::class,
                ],
            ],
            'authentication' => [
                'template' => Login::class,
                'default_user' => User::DEFAULT_NAME_GUEST,
                'login_path' => '/login',
            ],
            'authorization' => [
                'template' => null,
            ]
        ];
    }
}
