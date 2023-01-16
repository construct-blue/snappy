<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Component\Icon\Icon;

class DevInfoComponent extends ViewComponent
{
    public function render(): array
    {
        $result = [];
        if (defined('DEV_MODE') && DEV_MODE) {
            $result[] = [
                'info' => [
                    Icon::fromParams(['icon' => 'alert-circle']),
                    ' Development Mode',
                ],
            ];
        }
        if (defined('DEV_DOMAIN') && DEV_DOMAIN) {
            $result[] = [
                'info' => [
                    Icon::fromParams(['icon' => 'alert-circle']),
                    ' Staging Domain: ' . DEV_DOMAIN,
                ],
            ];
        }
        return $result;
    }
}
