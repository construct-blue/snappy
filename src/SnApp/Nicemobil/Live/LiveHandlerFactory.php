<?php

namespace Blue\SnApp\Nicemobil\Live;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LiveHandlerFactory
{
    public function __invoke(ContainerInterface $container): LiveHandler
    {
        return new LiveHandler($container->get(TemplateRendererInterface::class));
    }
}
