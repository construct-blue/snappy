<?php

declare(strict_types=1);

namespace Blue\Core\Http;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    UriBuilder::class => UriBuilderFactory::class
                ]
            ]
        ];
    }
}
