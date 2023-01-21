<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics;

use Blue\Core\Application\Handler\TemplateHandlerFactory;
use Blue\Snapps\Analytics\Day\DayHandler;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    DayHandler::class => TemplateHandlerFactory::class,
                ],
            ]
        ];
    }
}
