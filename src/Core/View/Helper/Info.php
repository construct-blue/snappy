<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\Environment\Environment;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\ViewComponent;

class Info extends ViewComponent
{
    public function render(): array
    {
        $result = [];
        $env = Environment::instance();
        if ($env->isDevMode()) {
            $result[] = [
                'info' => [
                    Icon::include('alert-circle'),
                    ' Development Mode',
                ],
            ];
        }
        if ($env->getDevDomain()) {
            $result[] = [
                'info' => [
                    Icon::include('alert-circle'),
                    ' Staging Domain: ' . $env->getDevDomain(),
                ],
            ];
        }
        return $result;
    }
}
