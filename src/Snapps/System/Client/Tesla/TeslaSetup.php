<?php

namespace Blue\Snapps\System\Client\Tesla;

use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;
use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

#[Entrypoint(__DIR__ . '/TeslaSetup.ts')]
class TeslaSetup extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Tesla Client',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        'h1' => 'Tesla Client',
                    ],
                    'main' => TemplateViewComponent::forTemplate(__DIR__ . '/TeslaSetup.phtml'),
                    SystemFooter::class => [],
                ]
            ],
        ];
    }
}
