<?php

declare(strict_types=1);

namespace Blue\SnApp\Cms;

use Blue\Core\View\ViewComponent;

class CmsFooter extends ViewComponent
{
    public function render(): array
    {
        return [
            'footer' => [
                'Not {userName}? ',
                'a href="{logoutPath}"' => 'Sign out'
            ]
        ];
    }
}
