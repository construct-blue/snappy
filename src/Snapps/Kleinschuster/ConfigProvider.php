<?php

namespace Blue\Snapps\Kleinschuster;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Models\Cms\Page\Handler\PageHandler;
use Blue\Snapps\Kleinschuster\Home\HomeHandler;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    HomeHandler::class => TemplateHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class
                ]
            ]
        ];
    }
}
