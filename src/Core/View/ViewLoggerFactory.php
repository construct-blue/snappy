<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ViewLoggerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ViewLogger($container->get(LoggerInterface::class));
    }
}
