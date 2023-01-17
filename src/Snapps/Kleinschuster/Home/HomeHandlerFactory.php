<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Snapps\Kleinschuster\Home\HomeHandler;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class HomeHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new HomeHandler($container->get(TemplateRendererInterface::class));
    }
}
