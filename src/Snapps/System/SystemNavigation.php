<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property bool $userIsGuest
 */
class SystemNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                ['a href="/"' => 'Home'],
                ['a href="{basePath}/users"' => 'Users'],
                ['a href="{basePath}/setup/tesla"' => 'NICEmobil Setup'],
                fn() => $this->userIsGuest ? [
                    'a href="{loginPath}"' => [
                        Icon::class => [
                            'icon' => 'log-in',
                        ],
                        ' Login'
                    ]
                ] : [
                    'a href="{basePath}/my-account"' => [
                        Icon::class => [
                            'icon' => 'user',
                        ],
                        ' {userName}'
                    ]
                ],
            ]
        ];
    }
}
