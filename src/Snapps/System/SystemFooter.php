<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Link;
use Blue\Core\View\ViewComponent;

/**
 * @property bool $isLoggedIn
 * @property string $loginPath
 * @property string $logoutPath
 * @property string $myAccountPath
 * @property string $activeUserName
 */
class SystemFooter extends ViewComponent
{
    public function render(): array
    {
        return [
            'footer' => fn() => $this->isLoggedIn ?
                [
                    'div' => [
                        Icon::for('user'),
                        ' ',
                        $this->activeUserName
                    ],
                    [
                        Link::class => [
                            'href' => $this->logoutPath,
                            'text' => 'Logout'
                        ],
                    ],
                    'span' => ' | ',
                    [
                        Link::class => [
                            'href' => $this->myAccountPath,
                            'text' => 'My Account',
                            'target' => 'popup'
                        ],
                    ],
                ] : [
                    Link::class => [
                        'href' => $this->loginPath,
                        'text' => 'Login'
                    ],

                ],
        ];
    }
}
