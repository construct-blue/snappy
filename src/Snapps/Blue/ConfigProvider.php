<?php

declare(strict_types=1);

namespace Blue\Snapps\Blue;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\Blue\Startpage\StartpageHandler;

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