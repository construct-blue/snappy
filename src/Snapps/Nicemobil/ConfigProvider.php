<?php

namespace Blue\Snapps\Nicemobil;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Models\Cms\Page\Handler\PageHandler;
use Blue\Snapps\Nicemobil\Live\LiveHandler;
use Blue\Snapps\Nicemobil\Live\LiveHandlerFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    LiveHandler::class => LiveHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class,
                ]
            ]
        ];
    }
}
