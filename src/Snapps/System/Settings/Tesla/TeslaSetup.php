<?php

namespace Blue\Snapps\System\Settings\Tesla;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\ClientScript;
use Blue\Core\View\Helper\PageWrapper;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\Settings\SettingsNavigation;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property SnappRoute $activeSnapp
 */
#[ClientScript(__DIR__ . '/TeslaSetup.ts')]
class TeslaSetup extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'NICEmobil',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        SettingsNavigation::class => [],
                    ],
                    'main' => Template::include(__DIR__ . '/TeslaSetup.phtml'),
                    new SystemFooter()
                ]
            ],
        ];
    }
}
