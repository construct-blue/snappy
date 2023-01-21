<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute $activeSnapp
 * @property bool $userIsGuest
 */
class SystemNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            fn() => $this->userIsGuest ?: [
                SystemMenuItems::class => []
            ],
        ];
    }
}
