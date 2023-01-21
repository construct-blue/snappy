<?php

namespace Blue\Snapps\Settings\Tesla;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\Application\SystemNavigation;
use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Settings\SettingsNavigation;

/**
 * @property IngressRoute $activeSnapp
 */
#[Entrypoint(__DIR__ . '/TeslaSetup.ts')]
class TeslaSetup extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->activeSnapp->getName(),
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        SettingsNavigation::class => [],
                    ],
                    'main' => TemplateViewComponent::forTemplate(__DIR__ . '/TeslaSetup.phtml'),
                ]
            ],
        ];
    }
}
