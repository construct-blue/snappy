<?php

declare(strict_types=1);

namespace Blue\SnApp\Blue;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\SnApp\Blue\Startpage\StartpageHandler;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    StartpageHandler::class => TemplateHandlerFactory::class
                ]
            ]
        ];
    }
}
