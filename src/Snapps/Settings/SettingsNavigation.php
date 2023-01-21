<?php

declare(strict_types=1);

namespace Blue\Snapps\Settings;

use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property string $basePath
 * @property string $activePath
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
                        'href' => '{basePath}/users',
                        'text' => 'Users',
                        'active' => "/users" === $this->activePath
                    ],
                ],
                [
                    Link::class => [
                        'href' => '{basePath}/setup/tesla',
                        'active' => "/setup/tesla" === $this->activePath,
                        'text' => 'NICEmobil',
                    ],
                ],
            ]
        ];
    }
}
