<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms;

use Blue\Core\Application\SystemNavigation;
use Blue\Core\Application\SystemMenuItems;
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
                SystemNavigation::class => [],
                SnappNavigation::class => [
                    'basePath' => '{basePath}'
                ],
            ]
        ];
    }
}
