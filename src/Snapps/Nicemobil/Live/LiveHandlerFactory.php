<?php

namespace Blue\Snapps\Nicemobil\Live;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LiveHandlerFactory
{
    public function __invoke(ContainerInterface $container): LiveHandler
    {
        return new LiveHandler($container->get(TemplateRendererInterface::class));
    }
}
