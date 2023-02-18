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
 * @property string $current
 * @property string $url
 */
#[Import(__DIR__ . '/TeslaSetup.ts')]
class TeslaSetup extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'NICEmobil',
                'messages' => $this->messages,
                'validations' => $this->validations,
                'body' => [
                    'header' => [
                        SystemNavigation::new($this->getModel()),
                        SettingsNavigation::new($this->getModel()),
                    ],
                    'main' => Template::include(__DIR__ . '/TeslaSetup.phtml',
                    [
                        'current' => $this->current,
                        'url' => $this->url,
                    ]),
                    SystemFooter::new($this->getModel())
                ]
            ],
        ];
    }
}
