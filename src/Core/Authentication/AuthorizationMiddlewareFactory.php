<?php

namespace Blue\Core\Authentication;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new AuthorizationMiddleware(
            $container->get(TemplateRendererInterface::class),
            $config['authorization']
        );
    }
}
