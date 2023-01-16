<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

class TemplateExceptionComponent extends ViewComponent
{
    public function render(): array
    {
        return [
            TemplateViewComponent::forTemplate(__DIR__ . '/TemplateException.phtml')
        ];
    }
}
