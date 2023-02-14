<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewComponent;

class RecursiveTestComponent extends ViewComponent
{
    public function render(): array
    {
        return [
            fn() => RecursiveTestComponent::new()
        ];
    }
}
