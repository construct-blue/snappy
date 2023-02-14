<?php

namespace Blue\Snapps\System\Settings\Tesla;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Import;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\Settings\SettingsNavigation;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

/**
 * @property SnappRoute $activeSnapp
 */
#[Import(__DIR__ . '/TeslaSetup.ts')]
class TeslaSetup extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'NICEmobil',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        SettingsNavigation::class => [],
                    ],
                    'main' => Template::include(__DIR__ . '/TeslaSetup.phtml'),
                    SystemFooter::new()
                ]
            ],
        ];
    }
}
