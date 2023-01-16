<?php

namespace Blue\Core\Application\Session\Window;

use Psr\Container\ContainerInterface;

class WindowMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): WindowMiddleware
    {
        return new WindowMiddleware();
    }
}
