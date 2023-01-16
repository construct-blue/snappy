<?php

declare(strict_types=1);

namespace Blue\SnApp\Cms;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\SnApp\Cms\Block\BlockHandler;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    BlockHandler::class => TemplateHandlerFactory::class
                ]
            ]
        ];
    }
}
