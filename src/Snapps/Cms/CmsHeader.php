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
class CmsHeader extends ViewComponent
{
    public function render(): array
    {
        return [
            'header' => [
                'nav' => [
                    ['a href="/"' => 'Home'],
                    ['a href="{basePath}/{snapp}"' => 'Content'],
                ],
                'h1' => [
                    'Content Manager',
                ],
                SnappNavigation::class => [
                    'basePath' => '{basePath}'
                ],
            ],
        ];
    }
}
