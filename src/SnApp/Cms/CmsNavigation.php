<?php

declare(strict_types=1);

namespace Blue\SnApp\Cms;

use Blue\Core\View\ViewComponent;

class CmsNavigation extends ViewComponent
{
    public function render(): array
    {
        return [
            'nav' => [
                ['a href="/"' => 'Home'],
                ['a href="/system/users"' => 'System'],
                ['a href="/cms/blocks"' => 'Blocks'],
            ]
        ];
    }
}
