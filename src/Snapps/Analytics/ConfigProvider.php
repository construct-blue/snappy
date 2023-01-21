<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\Analytics\Day\DayHandler;
use Blue\Snapps\Analytics\MyAccount\MyAccountHandler;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    MyAccountHandler::class => TemplateHandlerFactory::class,
                    DayHandler::class => TemplateHandlerFactory::class,
                ],
            ]
        ];
    }
}
