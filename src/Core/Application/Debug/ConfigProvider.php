<?php

declare(strict_types=1);

namespace Blue\Core\Application\Debug;

use Laminas\ConfigAggregator\ConfigAggregator;
use Mezzio\Container;
use Mezzio\Middleware\ErrorResponseGenerator;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            ConfigAggregator::ENABLE_CACHE => false,
            'debug' => true,
            'router' => [
                'fastroute' => [
                    'cache_enabled' => false,
                ],
            ],
            'dependencies' => [
                'factories' => [
                    ErrorResponseGenerator::class => Container\WhoopsErrorResponseGeneratorFactory::class,
                    'Mezzio\Whoops' => Container\WhoopsFactory::class,
                    'Mezzio\WhoopsPageHandler' => Container\WhoopsPageHandlerFactory::class,
                ],
            ],
            'authentication' => [
          #      'default_user' => User::DEFAULT_NAME_ADMIN,
            ],
            'whoops' => [
                'json_exceptions' => [
                    'display' => true,
                    'show_trace' => true,
                    'ajax_only' => true,
                ],
            ],
        ];
    }
}
