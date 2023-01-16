<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Psr\Container\ContainerInterface;

class ViewRendererMezzioBridgeFactory
{
    public function __invoke(ContainerInterface $container): ViewRendererMezzioBridge
    {
        return new ViewRendererMezzioBridge(clone $container->get(ViewRenderer::class));
    }
}
