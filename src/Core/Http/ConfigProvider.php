<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Blue\Core\Http\Uri\UriBuilder;
use Blue\Core\Http\Uri\UriBuilderFactory;

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
