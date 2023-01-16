<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Middleware\ErrorResponseGenerator;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouteCollectorInterface;
use Blue\Core\Application\Error\ErrorLoggerDelegator;
use Blue\Core\Application\Error\ErrorResponseDelegator;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAggregator::ENABLE_CACHE => true,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                MiddlewarePipeInterface::class => 'Mezzio\\ApplicationPipeline',
                RouteCollectorInterface::class => RouteCollector::class,
            ],
            'delegators' => [
                ErrorResponseGenerator::class => [
                    ErrorResponseDelegator::class
                ],
                ErrorHandler::class => [
                    ErrorLoggerDelegator::class
                ]
            ]
        ];
    }
}
