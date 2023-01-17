<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Snapps\System\Analytics\AnalyticsHandler;
use Blue\Snapps\System\Client\Tesla\TeslaSetupHandler;
use Blue\Snapps\System\User\UserHandler;
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
