<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms;

use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\SystemNavigation;

class CmsHeader extends ViewComponent
{
    public function render(): array
    {
        return [
            'header' => [
                SystemNavigation::new($this->getModel()),
                SnappNavigation::new($this->getModel()),
            ]
        ];
    }
}
