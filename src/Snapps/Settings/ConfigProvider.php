<?php

declare(strict_types=1);

namespace Blue\Snapps\Settings;

use Blue\Cms\Page\Handler\PageHandler;
use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\Analytics\Day\DayHandler;
use Blue\Snapps\Settings\Tesla\TeslaSetupHandler;
use Blue\Snapps\Settings\User\UserHandler;

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
                    DayHandler::class => TemplateHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class,
                ]
            ]
        ];
    }
}