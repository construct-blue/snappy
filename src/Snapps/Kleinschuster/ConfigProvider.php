<?php

namespace Blue\Snapps\Kleinschuster;

use Blue\Snapps\Kleinschuster\Home\HomeHandler;
use Blue\Snapps\Kleinschuster\Home\HomeHandlerFactory;

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
