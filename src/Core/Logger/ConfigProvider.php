<?php

namespace Blue\Core\Logger;

use Psr\Log\LoggerInterface;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'aliases' => [
                    LoggerInterface::class => Logger::class,
                ],
                'factories' => [
                    Logger::class => LoggerFactory::class,
                ]
            ]
        ];
    }
}
