<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

use Psr\Container\ContainerInterface;

class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new SessionMiddleware($container->get(SessionContainer::class));
    }
}
