<?php

declare(strict_types=1);

namespace Blue\Core\Application\Server;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Blue\Core\Application\Emitter\EmitterFactory;
use Blue\Core\Application\Session\SessionContainer;
use Blue\Core\Application\Session\SessionContainerFactory;
use Blue\Core\Application\Session\SessionMiddleware;
use Blue\Core\Application\Session\SessionMiddlewareFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                SessionMiddleware::class => SessionMiddlewareFactory::class,
                SessionContainer::class => SessionContainerFactory::class,
                EmitterInterface::class => EmitterFactory::class,
            ],
        ];
    }
}
