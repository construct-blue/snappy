<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class DefaultVariableMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DefaultVariableMiddleware($container->get(TemplateRendererInterface::class));
    }
}
