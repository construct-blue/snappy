<?php

declare(strict_types=1);

namespace Blue\SnApp\System;

use Blue\Core\View\ViewComponent;

class SystemNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                ['a href="/"' => 'Home'],
                ['a href="/cms/blocks"' => 'CMS'],
                ['a href="/system/users"' => 'Users'],
                ['a href="/system/analytics"' => 'Analytics'],
            ]
        ];
    }
}
