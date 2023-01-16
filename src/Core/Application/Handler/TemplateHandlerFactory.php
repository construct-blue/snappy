<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TemplateHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName)
    {
        return new $requestedName($container->get(TemplateRendererInterface::class));
    }
}
