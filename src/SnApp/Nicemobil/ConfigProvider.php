<?php

namespace Blue\SnApp\Nicemobil;

use Blue\SnApp\Nicemobil\Live\LiveHandler;
use Blue\SnApp\Nicemobil\Live\LiveHandlerFactory;

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
