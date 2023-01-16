<?php

declare(strict_types=1);

namespace Blue\Core\I18n;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokable' => [
                    Translator::class => Translator::class
                ]
            ]
        ];
    }
}
