<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Cms\Page\Handler\PageHandler;
use Blue\Snapps\System\Analytics\AnalyticsHandler;
use Blue\Snapps\System\Client\Tesla\TeslaSetupHandler;
use Blue\Snapps\System\MyAccount\MyAccountHandler;
use Blue\Snapps\System\User\UserHandler;
use Blue\Core\Application\Handler\TemplateHandlerFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'mezzio' => [
                'error_handler' => [
                    'template_404' => NotFound::class,
                ]
            ],
            'dependencies' => [
                'factories' => [
                    UserHandler::class => TemplateHandlerFactory::class,
                    TeslaSetupHandler::class => TemplateHandlerFactory::class,
                    AnalyticsHandler::class => TemplateHandlerFactory::class,
                    MyAccountHandler::class => TemplateHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class,
                ]
            ]
        ];
    }
}
