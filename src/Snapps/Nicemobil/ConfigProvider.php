<?php

namespace Blue\Snapps\Nicemobil;

use Blue\Snapps\Nicemobil\Live\LiveHandler;
use Blue\Snapps\Nicemobil\Live\LiveHandlerFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    LiveHandler::class => LiveHandlerFactory::class
                ]
            ]
        ];
    }
}
