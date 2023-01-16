<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

use Psr\Container\ContainerInterface;

class SessionContainerFactory
{
    public function __invoke(ContainerInterface $container): SessionContainer
    {
        return new SessionContainer();
    }
}
