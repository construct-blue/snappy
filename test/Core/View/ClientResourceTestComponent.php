<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Import;
use Blue\Core\View\ViewComponent;

#[Import(__DIR__ . '/script2.ts')]
class ClientResourceTestComponent extends ViewComponent
{
    public function render(): array
    {
        return [];
    }
}
