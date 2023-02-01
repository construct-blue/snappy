<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property string $startPath
 * @property string $cmsPath
 * @property string $settingsPath
 * @property string $analyticsPath
 * @property string $activePath
 * @property bool $isLoggedIn
 */
class SystemNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                [
                    Link::class => [
                        'href' => $this->startPath,
                        'active' => $this->startPath === $this->activePath,
                        'text' => 'Home',
                    ],
                ],
                fn() => !$this->isLoggedIn ?: [
                    fn() => !isset($this->cmsPath) ?: [
                        Link::class => [
                            'href' => $this->cmsPath,
                            'active' => $this->cmsPath === $this->activePath,
                            'text' => 'CMS',
                        ],
                    ],
                    fn() => !isset($this->settingsPath) ?: [
                        Link::class => [
                            'href' => $this->settingsPath,
                            'active' => $this->settingsPath === $this->activePath,
                            'text' => 'Settings',
                        ],
                    ],
                ]
            ],
        ];
    }
}
