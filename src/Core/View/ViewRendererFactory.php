<?php

namespace Blue\Core\View;

use Psr\Container\ContainerInterface;

class ViewRendererFactory
{
    public function __invoke(ContainerInterface $container): ViewRenderer
    {
        $debug = $container->get('config')['debug'] ?? false;
        return new ViewRenderer($container->get(ViewLogger::class), $debug);
    }
}
