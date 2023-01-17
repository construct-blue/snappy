<?php

declare(strict_types=1);

namespace Blue\Snapps\System;

use Blue\Core\Update\CachedVersion;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class SystemFooter extends ViewComponent
{
    public function render(): array
    {
        $version = new CachedVersion();
        return [
            'footer' => [
                'Not {userName}? ',
                'a href="{logoutPath}"' => 'Sign out',
                'p' => [
                    $version->getCurrent(),
                    fn() => $version->isNewAvailable() ? [
                        ' ',
                        'mark' => [
                            Icon::fromParams(['icon' => 'rss']),
                            ' ',
                            $version->getLatest()
                        ]
                    ] : []
                ],
            ]
        ];
    }
}
