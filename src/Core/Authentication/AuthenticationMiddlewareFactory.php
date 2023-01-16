<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new AuthenticationMiddleware(
            $container->get(TemplateRendererInterface::class),
            $config['authentication'],
            UserRepository::instance()
        );
    }
}
