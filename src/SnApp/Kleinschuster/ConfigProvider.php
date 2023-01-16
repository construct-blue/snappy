<?php

namespace Blue\SnApp\Kleinschuster;

use Blue\SnApp\Kleinschuster\Home\HomeHandler;
use Blue\SnApp\Kleinschuster\Home\HomeHandlerFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    HomeHandler::class => HomeHandlerFactory::class
                ]
            ]
        ];
    }
}
