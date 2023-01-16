<?php

declare(strict_types=1);

namespace Blue\SnApp\System;

use Blue\SnApp\System\Analytics\AnalyticsHandler;
use Blue\SnApp\System\Client\Tesla\TeslaSetupHandler;
use Blue\SnApp\System\User\UserHandler;
use Blue\Core\Application\Handler\TemplateHandlerFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    UserHandler::class => TemplateHandlerFactory::class,
                    TeslaSetupHandler::class => TemplateHandlerFactory::class,
                    AnalyticsHandler::class => TemplateHandlerFactory::class,
                ]
            ]
        ];
    }
}
