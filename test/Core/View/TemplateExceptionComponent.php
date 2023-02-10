<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;

class TemplateExceptionComponent extends ViewComponent
{
    public function render(): array
    {
        return [
            Template::include(__DIR__ . '/TemplateException.phtml')
        ];
    }
}
