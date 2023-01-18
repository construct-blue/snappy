<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\Cms\Block\BlockHandler;
use Blue\Snapps\Cms\Page\PageHandler;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    BlockHandler::class => TemplateHandlerFactory::class,
                    PageHandler::class => TemplateHandlerFactory::class,
                ]
            ]
        ];
    }
}
