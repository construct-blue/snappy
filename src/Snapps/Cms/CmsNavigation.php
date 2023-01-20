<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

/**
 * @property bool $userIsGuest
 * @property string $userName
 * @property string $basePath
 * @property string $loginPath
 */
class CmsNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                ['a href="/"' => 'Home'],
                ['a href="{basePath}/pages/{snapp}"' => 'Pages'],
                ['a href="{basePath}/blocks/{snapp}"' => 'Blocks'],
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
