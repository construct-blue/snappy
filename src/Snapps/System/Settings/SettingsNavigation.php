<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings;

use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property string $settingsPath
 * @property string $teslaPath
 * @property string $currentPath
 * @property bool $userIsGuest
 */
class SettingsNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                [
                    Link::class => [
                        'href' => $this->settingsPath,
                        'text' => 'Users',
                        'active' => $this->settingsPath === $this->currentPath
                    ],
                ],
                [
                    Link::class => [
                        'href' => $this->teslaPath,
                        'active' => $this->teslaPath === $this->currentPath,
                        'text' => 'NICEmobil',
                    ],
                ],
            ]
        ];
    }
}
